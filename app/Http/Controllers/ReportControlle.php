<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\PerformanceReview;
use App\Models\Task;
use App\Models\Training;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class ReportControlle extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        return view('reports.index');
    }

    public function attendance(Request $request)
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $employeeId = $request->input('employee_id');
        
        // Interogare pentru a obține rapoarte de prezență
        $query = Attendance::with('user')
                           ->whereBetween('date', [$startDate, $endDate]);
        
        if ($employeeId) {
            $query->where('user_id', $employeeId);
        }
        
        $attendanceRecords = $query->orderBy('date', 'desc')
                                  ->paginate(15);
        
        $employees = User::all();
        
        return view('reports.attendance', compact('attendanceRecords', 'employees', 'startDate', 'endDate', 'employeeId'));
    }

    public function performance(Request $request)
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $startDate = $request->input('start_date', now()->subYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $employeeId = $request->input('employee_id');
        
        // Interogare pentru a obține rapoarte de performanță
        $query = PerformanceReview::with(['employee', 'reviewer'])
                                 ->whereBetween('review_date', [$startDate, $endDate]);
        
        if ($employeeId) {
            $query->where('user_id', $employeeId);
        }
        
        $performanceReviews = $query->orderBy('review_date', 'desc')
                                   ->paginate(15);
        
        $employees = User::all();
        
        return view('reports.performance', compact('performanceReviews', 'employees', 'startDate', 'endDate', 'employeeId'));
    }

    public function tasks(Request $request)
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $status = $request->input('status');
        $priority = $request->input('priority');
        $employeeId = $request->input('employee_id');
        
        // Interogare pentru a obține rapoarte de task-uri
        $query = Task::with(['assignee', 'creator']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($priority) {
            $query->where('priority', $priority);
        }
        
        if ($employeeId) {
            $query->where('assigned_to', $employeeId);
        }
        
        $tasks = $query->orderBy('created_at', 'desc')
                      ->paginate(15);
        
        $employees = User::all();
        $statuses = ['todo', 'in_progress', 'review', 'completed'];
        $priorities = ['low', 'medium', 'high'];
        
        return view('reports.tasks', compact('tasks', 'employees', 'statuses', 'priorities', 'status', 'priority', 'employeeId'));
    }

    public function trainings(Request $request)
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $type = $request->input('type');
        $status = $request->input('status');
        
        // Interogare pentru a obține rapoarte de training-uri
        $query = Training::with('creator');
        
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $trainings = $query->orderBy('created_at', 'desc')
                          ->paginate(15);
        
        $types = ['tehnical', 'management', 'personal', 'communication', 'leadership'];
        $statuses = ['active', 'draft', 'archived'];
        
        return view('reports.trainings', compact('trainings', 'types', 'statuses', 'type', 'status'));
    }

    public function surveys(Request $request)
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $type = $request->input('type');
        $status = $request->input('status');
        
        // Interogare pentru a obține rapoarte de sondaje
        $query = Survey::with('creator');
        
        if ($type) {
            $query->where('type', $type);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $surveys = $query->orderBy('created_at', 'desc')
                        ->paginate(15);
        
        $types = ['satisfaction', 'feedback', 'initiative', '360', 'culture'];
        $statuses = ['draft', 'active', 'closed'];
        
        return view('reports.surveys', compact('surveys', 'types', 'statuses', 'type', 'status'));
    }

    public function export(Request $request)
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $reportType = $request->input('report_type');
        $format = $request->input('format', 'excel');
        
        // Aici implementează logica de export pentru diferite tipuri de rapoarte
        // Acest cod este doar un schelet și trebuie completat cu logica reală de export
        
        return redirect()->back()->with('success', 'Raportul a fost exportat cu succes!');
    }
}