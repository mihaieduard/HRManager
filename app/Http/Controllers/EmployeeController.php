<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;
use App\Models\Task;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin,HR']);
    }

    public function index()
    {
        $employees = User::orderBy('name')->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:Admin,HR,Angajat',
            'position' => 'required|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after:contract_start',
            'previous_experience' => 'nullable|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'position' => $request->position,
            'salary' => $request->salary,
            'contract_start' => $request->contract_start,
            'contract_end' => $request->contract_end,
            'previous_experience' => $request->previous_experience
        ]);

        return redirect()->route('employees.index')
                         ->with('success', 'Angajatul a fost adăugat cu succes!');
    }

    public function show(User $employee)
    {
        $attendances = Attendance::where('user_id', $employee->id)
                                ->orderBy('date', 'desc')
                                ->take(10)
                                ->get();
                                
        $tasks = Task::where('user_id', $employee->id)
                     ->orderBy('due_date', 'desc')
                     ->take(10)
                     ->get();
                     
        $completedTasks = Task::where('user_id', $employee->id)
                             ->where('completed', true)
                             ->count();
                             
        $pendingTasks = Task::where('user_id', $employee->id)
                           ->where('completed', false)
                           ->count();
                           
        $performance = Task::where('user_id', $employee->id)
                         ->where('completed', true)
                         ->whereMonth('completed_date', now()->month)
                         ->sum('points');
                         
        return view('employees.show', compact('employee', 'attendances', 'tasks', 'completedTasks', 'pendingTasks', 'performance'));
    }

    public function edit(User $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'role' => 'required|string|in:Admin,HR,Angajat',
            'position' => 'required|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after:contract_start',
            'previous_experience' => 'nullable|string'
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'position' => $request->position,
            'salary' => $request->salary,
            'contract_start' => $request->contract_start,
            'contract_end' => $request->contract_end,
            'previous_experience' => $request->previous_experience
        ];
        
        // Actualizează parola doar dacă a fost furnizată
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $data['password'] = Hash::make($request->password);
        }
        
        $employee->update($data);
        
        return redirect()->route('employees.index')
                         ->with('success', 'Informațiile angajatului au fost actualizate cu succes!');
    }

    public function destroy(User $employee)
    {   
        $userId = request()->user()->id ?? null;
        // Verifică dacă utilizatorul nu își șterge propriul cont
        if ($userId === $employee->id) {
            return redirect()->route('employees.index')
                             ->with('error', 'Nu poți șterge propriul cont!');
        }
        
        $employee->delete();
        
        return redirect()->route('employees.index')
                         ->with('success', 'Angajatul a fost șters cu succes!');
    }
}