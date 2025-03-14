<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Attendance;
use App\Models\Post;
use App\Models\Training;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        $user = request()->user();
        $tasks = Task::where('user_id', $user->id)
                     ->where('completed', false)
                     ->orderBy('due_date')
                     ->take(5)
                     ->get();
        
        $attendance = Attendance::where('user_id', $user->id)
                               ->whereDate('date', today())
                               ->first();
        
        $recentPosts = Post::with('user')
                          ->orderBy('created_at', 'desc')
                          ->take(5)
                          ->get();
        
        $trainings = Training::orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
        
        return view('dashboard', compact('user', 'tasks', 'attendance', 'recentPosts', 'trainings'));
    }
}