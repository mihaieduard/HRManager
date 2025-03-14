<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function isAdminOrHR($user)
    {
        if (!$user) {
            return false;
        }
        
        return $user->isAdmin() || $user->isHR();
    }

    // public function index()
    // {
    //     $user = request()->user();
        
    //     if (!$user) {
    //         return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
    //     }

    //     if ($user->role === 'Admin' || $user->role === 'HR') {
    //         // Admin și HR pot vedea toate evaluările
    //         $reviews = PerformanceReview::with(['employee', 'reviewer'])
    //                                   ->orderBy('review_date', 'desc')
    //                                   ->paginate(10);
    //     } else {
    //         // Angajații văd doar evaluările lor
    //         $reviews = PerformanceReview::with(['employee', 'reviewer'])
    //                                   ->where('user_id', $user->id)
    //                                   ->orderBy('review_date', 'desc')
    //                                   ->paginate(10);
    //     }

    //     return view('performance.index', compact('reviews'));
    // }

    public function index()
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        if ($this->isAdminOrHR($user)) {
            // Admin și HR pot vedea toate evaluările
            $reviews = PerformanceReview::with(['employee', 'reviewer'])
                                      ->orderBy('review_date', 'desc')
                                      ->paginate(10);
        } else {
            // Angajații văd doar evaluările lor
            $reviews = PerformanceReview::with(['employee', 'reviewer'])
                                      ->where('user_id', $user->id)
                                      ->orderBy('review_date', 'desc')
                                      ->paginate(10);
        }

        return view('performance.index', compact('reviews'));
    }

    // public function create()
    // {
    //     $user = request()->user();
        
    //     if (!$user) {
    //         return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
    //     }

    //     dd([
    //         'user_role' => $user->role,
    //         'is_admin_exact_match' => $user->role === 'Admin',
    //         'is_hr_exact_match' => $user->role === 'HR',
    //         'is_admin_case_insensitive' => strtolower($user->role) === strtolower('Admin'),
    //         'is_hr_case_insensitive' => strtolower($user->role) === strtolower('HR'),
    //         'role_type' => gettype($user->role),
    //         'role_length' => strlen($user->role),
    //     ]);

    //     // Doar Admin și HR pot crea evaluări
    //     if ($user->role !== 'Admin' && $user->role !== 'HR') {
    //         return redirect()->route('performance.index')
    //                        ->with('error', 'Nu ai permisiunea să accesezi această pagină!');
    //     }
        
    //     $employees = User::all();
    //     return view('performance.create', compact('employees'));
    // }
    public function create()
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Debug pentru a vedea relația role și valorile
        // dd([
        //     'user_id' => $user->id,
        //     'user_name' => $user->name,
        //     'role_id' => $user->role_id,
        //     'role_relation_exists' => $user->role ? true : false,
        //     'role_name' => $user->role ? $user->role->name : null,
        //     'is_admin_method' => $user->isAdmin(),
        //     'is_hr_method' => $user->isHR(),
        // ]);

        // Doar Admin și HR pot crea evaluări
        if (!$this->isAdminOrHR($user)) {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să accesezi această pagină!');
        }

        $employees = User::all();
        return view('performance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Doar Admin și HR pot crea evaluări
        if ($user->role !== 'Admin' && $user->role !== 'HR') {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să efectuezi această acțiune!');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'review_date' => 'required|date',
            'period_start_month' => 'required|integer|between:1,12',
            'period_start_year' => 'required|integer|between:2000,2050',
            'period_end_month' => 'required|integer|between:1,12',
            'period_end_year' => 'required|integer|between:2000,2050',
            'accomplishments' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals' => 'nullable|string',
            'technical_skills_rating' => 'required|integer|between:1,5',
            'technical_skills_comments' => 'nullable|string',
            'communication_rating' => 'required|integer|between:1,5',
            'communication_comments' => 'nullable|string',
            'teamwork_rating' => 'required|integer|between:1,5',
            'teamwork_comments' => 'nullable|string',
            'initiative_rating' => 'required|integer|between:1,5',
            'initiative_comments' => 'nullable|string',
            'reliability_rating' => 'required|integer|between:1,5',
            'reliability_comments' => 'nullable|string',
            'overall_rating' => 'required|integer|between:1,5',
            'overall_comments' => 'nullable|string',
            'status' => 'required|in:draft,submitted',
        ]);

        // Verifică dacă există deja o evaluare pentru acest angajat și această perioadă
        $existingReview = PerformanceReview::where('user_id', $request->user_id)
                                         ->where('period_start_month', $request->period_start_month)
                                         ->where('period_start_year', $request->period_start_year)
                                         ->where('period_end_month', $request->period_end_month)
                                         ->where('period_end_year', $request->period_end_year)
                                         ->first();

        if ($existingReview) {
            return redirect()->back()
                           ->with('error', 'Există deja o evaluare pentru acest angajat și această perioadă!')
                           ->withInput();
        }

        $reviewData = $request->all();
        $reviewData['reviewer_id'] = $user->id;

        PerformanceReview::create($reviewData);

        return redirect()->route('performance.index')
                       ->with('success', 'Evaluarea a fost creată cu succes!');
    }

    public function show(PerformanceReview $performance)
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Verifică dacă utilizatorul are dreptul să vadă această evaluare
        if ($user->id !== $performance->reviewer_id && 
            $user->id !== $performance->user_id && 
            $user->role !== 'Admin' && 
            $user->role !== 'HR') {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să vezi această evaluare!');
        }

        return view('performance.show', compact('performance'));
    }

    public function edit(PerformanceReview $performance)
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Doar creatorul evaluării sau Admin/HR poate edita evaluarea dacă este în draft
        if (($user->id !== $performance->reviewer_id && $user->role !== 'Admin' && $user->role !== 'HR') || 
            $performance->status !== 'draft') {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să editezi această evaluare!');
        }

        $employees = User::all();
        return view('performance.edit', compact('performance', 'employees'));
    }

    public function update(Request $request, PerformanceReview $performance)
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Doar creatorul evaluării sau Admin/HR poate actualiza evaluarea dacă este în draft
        if (($user->id !== $performance->reviewer_id && $user->role !== 'Admin' && $user->role !== 'HR') || 
            $performance->status !== 'draft') {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să actualizezi această evaluare!');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'review_date' => 'required|date',
            'period_start_month' => 'required|integer|between:1,12',
            'period_start_year' => 'required|integer|between:2000,2050',
            'period_end_month' => 'required|integer|between:1,12',
            'period_end_year' => 'required|integer|between:2000,2050',
            'accomplishments' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals' => 'nullable|string',
            'technical_skills_rating' => 'required|integer|between:1,5',
            'technical_skills_comments' => 'nullable|string',
            'communication_rating' => 'required|integer|between:1,5',
            'communication_comments' => 'nullable|string',
            'teamwork_rating' => 'required|integer|between:1,5',
            'teamwork_comments' => 'nullable|string',
            'initiative_rating' => 'required|integer|between:1,5',
            'initiative_comments' => 'nullable|string',
            'reliability_rating' => 'required|integer|between:1,5',
            'reliability_comments' => 'nullable|string',
            'overall_rating' => 'required|integer|between:1,5',
            'overall_comments' => 'nullable|string',
            'status' => 'required|in:draft,submitted',
        ]);

        // Verifică dacă există o altă evaluare pentru același angajat și aceeași perioadă
        $existingReview = PerformanceReview::where('user_id', $request->user_id)
                                         ->where('period_start_month', $request->period_start_month)
                                         ->where('period_start_year', $request->period_start_year)
                                         ->where('period_end_month', $request->period_end_month)
                                         ->where('period_end_year', $request->period_end_year)
                                         ->where('id', '!=', $performance->id)
                                         ->first();

        if ($existingReview) {
            return redirect()->back()
                           ->with('error', 'Există deja o evaluare pentru acest angajat și această perioadă!')
                           ->withInput();
        }

        $performance->update($request->all());

        return redirect()->route('performance.index')
                       ->with('success', 'Evaluarea a fost actualizată cu succes!');
    }

    public function destroy(PerformanceReview $performance)
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Doar creatorul evaluării sau Admin/HR poate șterge evaluarea dacă este în draft
        if (($user->id !== $performance->reviewer_id && $user->role !== 'Admin' && $user->role !== 'HR') || 
            $performance->status !== 'draft') {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să ștergi această evaluare!');
        }

        $performance->delete();

        return redirect()->route('performance.index')
                       ->with('success', 'Evaluarea a fost ștearsă cu succes!');
    }

    public function submit(PerformanceReview $performance)
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Doar creatorul evaluării sau Admin/HR poate trimite evaluarea dacă este în draft
        if (($user->id !== $performance->reviewer_id && $user->role !== 'Admin' && $user->role !== 'HR') || 
            $performance->status !== 'draft') {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să trimiți această evaluare!');
        }

        $performance->status = 'submitted';
        $performance->save();

        return redirect()->route('performance.index')
                       ->with('success', 'Evaluarea a fost trimisă cu succes!');
    }

    public function acknowledge(Request $request, PerformanceReview $performance)
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Doar angajatul evaluat poate confirma evaluarea dacă este trimisă și neconfirmată
        if ($user->id !== $performance->user_id || 
            $performance->status !== 'submitted' || 
            $performance->employee_acknowledged) {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să confirmi această evaluare!');
        }

        $request->validate([
            'employee_comments' => 'nullable|string',
        ]);

        $performance->employee_comments = $request->employee_comments;
        $performance->employee_acknowledged = true;
        $performance->employee_acknowledged_date = now();
        $performance->status = 'acknowledged';
        $performance->save();

        return redirect()->route('performance.index')
                       ->with('success', 'Evaluarea a fost confirmată cu succes!');
    }

    public function report()
    {
        $user = request()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Trebuie să fii autentificat pentru a accesa această pagină!');
        }

        // Doar Admin și HR pot vedea rapoarte
        if ($user->role !== 'Admin' && $user->role !== 'HR') {
            return redirect()->route('performance.index')
                           ->with('error', 'Nu ai permisiunea să accesezi această pagină!');
        }

        $employees = User::all();
        $selectedYear = request('year', Carbon::now()->year);
        $selectedEmployee = request('user_id');

        $query = PerformanceReview::where('status', 'acknowledged')
                                 ->where(function($q) use ($selectedYear) {
                                     $q->where('period_start_year', $selectedYear)
                                       ->orWhere('period_end_year', $selectedYear);
                                 });

        if ($selectedEmployee) {
            $query->where('user_id', $selectedEmployee);
        }

        $reviews = $query->get();

        // Calculează statistici
        $statistics = [];
        foreach ($employees as $employee) {
            $employeeReviews = $reviews->where('user_id', $employee->id);
            
            if ($employeeReviews->count() > 0) {
                $avgTechnical = $employeeReviews->avg('technical_skills_rating');
                $avgCommunication = $employeeReviews->avg('communication_rating');
                $avgTeamwork = $employeeReviews->avg('teamwork_rating');
                $avgInitiative = $employeeReviews->avg('initiative_rating');
                $avgReliability = $employeeReviews->avg('reliability_rating');
                $avgOverall = $employeeReviews->avg('overall_rating');
                
                $statistics[$employee->id] = [
                    'name' => $employee->name,
                    'reviews_count' => $employeeReviews->count(),
                    'avg_technical' => round($avgTechnical, 1),
                    'avg_communication' => round($avgCommunication, 1),
                    'avg_teamwork' => round($avgTeamwork, 1),
                    'avg_initiative' => round($avgInitiative, 1),
                    'avg_reliability' => round($avgReliability, 1),
                    'avg_overall' => round($avgOverall, 1),
                ];
            }
        }

        $years = range(Carbon::now()->year - 5, Carbon::now()->year);

        return view('performance.report', compact('employees', 'reviews', 'statistics', 'selectedYear', 'selectedEmployee', 'years'));
    }
}