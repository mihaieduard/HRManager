<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Încarcă postările recente pentru pagina principală de colaborare
        $posts = Post::with(['user', 'likes', 'comments.user'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        
        // Încarcă evenimentele viitoare
        $upcomingEvents = Event::where('start_time', '>', now())
                              ->orderBy('start_time', 'asc')
                              ->take(3)
                              ->get();
        
        return view('collaboration.index', compact('posts', 'upcomingEvents'));
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'type' => 'required|in:text,image,document,link,question',
            'visibility' => 'required|in:public,team,private',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content;
        $post->type = $request->type;
        $post->visibility = $request->visibility;
        
        // Procesează atașamentul dacă există
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');
            $post->attachment = $path;
            $post->attachment_type = $file->getClientMimeType();
        }
        
        $post->save();
        
        return redirect()->route('collaboration.index')->with('success', 'Postarea a fost creată cu succes!');
    }

    public function destroyPost(Post $post)
    {
        // Verifică dacă utilizatorul poate șterge postarea
        $user = request()->user();
        if (Auth::id() !== $post->user_id && !$user->isAdmin()) {
            return redirect()->route('collaboration.index')->with('error', 'Nu aveți permisiunea să ștergeți această postare.');
        }
        
        // Șterge atașamentul dacă există
        if ($post->attachment) {
            Storage::disk('public')->delete($post->attachment);
        }
        
        // Șterge postarea și, implicit, toate comentariile și aprecierile asociate
        $post->delete();
        
        return redirect()->route('collaboration.index')->with('success', 'Postarea a fost ștearsă cu succes!');
    }

    public function likePost(Request $request, Post $post)
    {
        // Verifică dacă utilizatorul a apreciat deja postarea
        $existingLike = Like::where('user_id', Auth::id())
                            ->where('likeable_id', $post->id)
                            ->where('likeable_type', Post::class)
                            ->first();
        
        if ($existingLike) {
            // Dacă există deja o apreciere, o elimină (toggle)
            $existingLike->delete();
            $message = 'Aprecierea a fost eliminată!';
        } else {
            // Altfel, adaugă o nouă apreciere
            $like = new Like();
            $like->user_id = Auth::id();
            $like->likeable_id = $post->id;
            $like->likeable_type = Post::class;
            $like->save();
            $message = 'Postarea a fost apreciată!';
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'likesCount' => $post->likes()->count()
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }

    public function commentPost(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->commentable_id = $post->id;
        $comment->commentable_type = Post::class;
        $comment->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comentariul a fost adăugat!',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'user' => [
                        'name' => Auth::user()->name,
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random'
                    ]
                ]
            ]);
        }
        
        return redirect()->back()->with('success', 'Comentariul a fost adăugat!');
    }

    public function events()
    {
        $events = Event::with('creator')
                       ->orderBy('start_time', 'asc')
                       ->paginate(10);
        
        return view('collaboration.events', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',
        ]);
        
        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->location = $request->location;
        $event->type = $request->type;
        $event->created_by = Auth::id();
        $event->save();
        
        return redirect()->route('collaboration.events')->with('success', 'Evenimentul a fost creat cu succes!');
    }
}