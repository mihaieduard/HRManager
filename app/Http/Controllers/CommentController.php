<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {   
        $userId = request()->user()->id ?? null;
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);
        
        $post = Post::findOrFail($request->post_id);
        
        Comment::create([
            'user_id' => $userId,
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);
        
        // Actualizează data ultimei interacțiuni cu postarea
        $post->last_interaction = now();
        $post->save();
        
        return redirect()->back()->with('success', 'Comentariul a fost adăugat cu succes!');
    }
    
    public function destroy(Comment $comment)
    {   
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să șteargă acest comentariu
        if ($comment->user_id !== $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->back()->with('error', 'Nu ai permisiunea să ștergi acest comentariu!');
        }
        
        $post = $comment->post;
        
        $comment->delete();
        
        // Actualizează data ultimei interacțiuni cu postarea
        $post->last_interaction = now();
        $post->save();
        
        return redirect()->back()->with('success', 'Comentariul a fost șters cu succes!');
    }
}