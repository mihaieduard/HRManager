<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $posts = Post::with(['user', 'comments.user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('posts.index', compact('posts'));
    }
    
    public function create()
    {
        return view('posts.create');
    }
    
    public function store(Request $request)
    {   
        $userId = request()->user()->id ?? null;
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);
        
        $data = [
            'user_id' => $userId,
            'title' => $request->title,
            'content' => $request->content,
            'last_interaction' => now(),
        ];
        
        // Procesează fișierul atașat dacă există
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $data['attachment'] = $path;
        }
        
        Post::create($data);
        
        return redirect()->route('posts.index')
                         ->with('success', 'Postarea a fost creată cu succes!');
    }
    
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);
        
        // Actualizează data ultimei interacțiuni
        $post->last_interaction = now();
        $post->save();
        
        return view('posts.show', compact('post'));
    }
    
    public function edit(Post $post)
    {   
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să editeze această postare
        if ($post->user_id !== $userId&& !$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('posts.index')
                           ->with('error', 'Nu ai permisiunea să editezi această postare!');
        }
        
        return view('posts.edit', compact('post'));
    }
    
    public function update(Request $request, Post $post)
    {
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să editeze această postare
        if ($post->user_id !== $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('posts.index')
                           ->with('error', 'Nu ai permisiunea să editezi această postare!');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);
        
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'last_interaction' => now(),
        ];
        
        // Procesează fișierul atașat dacă există
        if ($request->hasFile('attachment')) {
            // Șterge fișierul vechi dacă există
            if ($post->attachment) {
                Storage::disk('public')->delete($post->attachment);
            }
            
            $path = $request->file('attachment')->store('attachments', 'public');
            $data['attachment'] = $path;
        }
        
        $post->update($data);
        
        return redirect()->route('posts.show', $post)
                         ->with('success', 'Postarea a fost actualizată cu succes!');
    }
    
    public function vote(Request $request, Post $post)
    {
        $direction = $request->direction === 'up' ? 1 : -1;
        
        // Actualizează voturile și data ultimei interacțiuni
        $post->votes += $direction;
        $post->last_interaction = now();
        $post->save();
        
        return redirect()->back()->with('success', 'Vot înregistrat cu succes!');
    }
    
    public function destroy(Post $post)
    {   
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să șteargă această postare
        if ($post->user_id !== $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('posts.index')
                           ->with('error', 'Nu ai permisiunea să ștergi această postare!');
        }
        
        // Șterge fișierul atașat dacă există
        if ($post->attachment) {
            Storage::disk('public')->delete($post->attachment);
        }
        
        // Șterge toate comentariile asociate
        $post->comments()->delete();
        
        $post->delete();
        
        return redirect()->route('posts.index')
                         ->with('success', 'Postarea a fost ștearsă cu succes!');
    }
    
    // Metoda pentru ștergerea automată a postărilor vechi
    // Aceasta ar trebui rulată printr-un task programat
    public function deleteInactive()
    {
        $cutoffDate = now()->subDays(30); // Postări fără interacțiune în ultimele 30 de zile
        
        $posts = Post::where('last_interaction', '<', $cutoffDate)->get();
        
        foreach ($posts as $post) {
            if ($post->attachment) {
                Storage::disk('public')->delete($post->attachment);
            }
            $post->comments()->delete();
            $post->delete();
        }
        
        return redirect()->route('posts.index')
                         ->with('success', 'Postările inactive au fost șterse automat!');
    }
}