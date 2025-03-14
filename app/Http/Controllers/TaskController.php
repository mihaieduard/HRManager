<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = request()->user();
        
        if ($user->isAdmin() || $user->isHR()) {
            // Administrații și HR pot vedea task-urile tuturor
            $tasks = Task::with('user')
                        ->orderBy('due_date')
                        ->paginate(10);
        } else {
            // Angajații văd doar task-urile lor
            $tasks = Task::where('user_id', $user->id)
                        ->orderBy('due_date')
                        ->paginate(10);
        }
        
        return view('tasks.index', compact('tasks'));
    }
    
    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|integer|min:1|max:5',
            'points' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Verifică dacă utilizatorul are permisiunea să asigneze task-uri altora
        if ($request->user_id != $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->back()
                           ->with('error', 'Nu ai permisiunea să asignezi task-uri altor utilizatori!')
                           ->withInput();
        }
        
        Task::create($request->all());
        
        return redirect()->route('tasks.index')
                         ->with('success', 'Task-ul a fost creat cu succes!');
    }
    
    public function show(Task $task)
    {   
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să vadă acest task
        if ($task->user_id != $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('tasks.index')
                           ->with('error', 'Nu ai permisiunea să vezi acest task!');
        }
        
        return view('tasks.show', compact('task'));
    }
    
    public function edit(Task $task)
    {
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să editeze acest task
        if ($task->user_id != $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('tasks.index')
                           ->with('error', 'Nu ai permisiunea să editezi acest task!');
        }
        
        $users = User::all();
        return view('tasks.edit', compact('task', 'users'));
    }
    
    public function update(Request $request, Task $task)
    {
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să editeze acest task
        if ($task->user_id != $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('tasks.index')
                           ->with('error', 'Nu ai permisiunea să editezi acest task!');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|integer|min:1|max:5',
            'points' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Verifică dacă utilizatorul are permisiunea să reasigneze task-ul
        if ($request->user_id != $task->user_id && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->back()
                           ->with('error', 'Nu ai permisiunea să reasignezi acest task!')
                           ->withInput();
        }
        
        $task->update($request->all());
        
        return redirect()->route('tasks.index')
                         ->with('success', 'Task-ul a fost actualizat cu succes!');
    }
    
    public function destroy(Task $task)
    {
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunea să șteargă acest task
        if ($task->user_id != $userId && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('tasks.index')
                           ->with('error', 'Nu ai permisiunea să ștergi acest task!');
        }
        
        $task->delete();
        
        return redirect()->route('tasks.index')
                         ->with('success', 'Task-ul a fost șters cu succes!');
    }
    
    public function complete($id)
    {   
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        $task = Task::findOrFail($id);
        
        // Verifică dacă utilizatorul are dreptul să marcheze task-ul ca finalizat
        if ($userId !== $task->user_id && !$user->isAdmin() && !$user->isHR()) {
            return redirect()->back()->with('error', 'Nu ai permisiunea să finalizezi acest task!');
        }
        
        $task->completed = true;
        $task->completed_date = now();
        $task->save();
        
        return redirect()->back()->with('success', 'Task finalizat cu succes!');
    }
    
    public function report()
    {   
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Doar Admin și HR pot vedea rapoarte
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('tasks.index')
                           ->with('error', 'Nu ai permisiunea să accesezi această pagină!');
        }
        
        $users = User::all();
        $startDate = request('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = request('end_date', Carbon::now()->endOfMonth()->toDateString());
        $selectedUser = request('user_id');
        
        $query = Task::whereBetween('due_date', [$startDate, $endDate]);
        
        if ($selectedUser) {
            $query->where('user_id', $selectedUser);
        }
        
        $tasks = $query->get();
        
        // Calculează statistici
        $statistics = [];
        foreach ($users as $user) {
            $userTasks = $tasks->where('user_id', $user->id);
            
            if ($userTasks->count() > 0) {
                $completedTasks = $userTasks->where('completed', true)->count();
                $totalPoints = $userTasks->where('completed', true)->sum('points');
                
                $statistics[$user->id] = [
                    'name' => $user->name,
                    'total_tasks' => $userTasks->count(),
                    'completed_tasks' => $completedTasks,
                    'completion_rate' => $userTasks->count() > 0 ? round(($completedTasks / $userTasks->count()) * 100, 1) : 0,
                    'total_points' => $totalPoints,
                ];
            }
        }
        
        return view('tasks.report', compact('users', 'tasks', 'statistics', 'startDate', 'endDate', 'selectedUser'));
    }
}