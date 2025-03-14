<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $user = request()->user();
        if ($user->isAdmin() || $user->isHR()) {
            // Admin și HR pot vedea prezențele tuturor
            $attendances = Attendance::with('user')
                ->orderBy('date', 'desc')
                ->paginate(10);
        } else {
            // Angajații văd doar prezențele lor
            $attendances = Attendance::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->paginate(10);
        }

        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $user = request()->user();
        // Doar Admin și HR pot crea prezențe pentru alți angajați
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('attendances.index')
                ->with('error', 'Nu ai permisiunea să accesezi această pagină!');
        }

        $users = User::all();
        return view('attendances.create', compact('users'));
    }

    public function store(Request $request)
    {
        $user = request()->user();
        // Doar Admin și HR pot crea prezențe pentru alți angajați
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('attendances.index')
                ->with('error', 'Nu ai permisiunea să efectuezi această acțiune!');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'clock_in' => 'required',
            'clock_out' => 'nullable|after:clock_in',
            'notes' => 'nullable|string',
        ]);

        // Verifică dacă există deja o prezență pentru acest angajat și această dată
        $existingAttendance = Attendance::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()
                ->with('error', 'Există deja o înregistrare pentru acest angajat în această dată!')
                ->withInput();
        }

        Attendance::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'notes' => $request->notes,
        ]);

        return redirect()->route('attendances.index')
            ->with('success', 'Prezența a fost înregistrată cu succes!');
    }

    public function edit(Attendance $attendance)
    {
        $user = request()->user();
        // Doar Admin și HR pot edita prezențele
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('attendances.index')
                ->with('error', 'Nu ai permisiunea să accesezi această pagină!');
        }

        $users = User::all();
        return view('attendances.edit', compact('attendance', 'users'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $user = request()->user();
        // Doar Admin și HR pot actualiza prezențele
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('attendances.index')
                ->with('error', 'Nu ai permisiunea să efectuezi această acțiune!');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'clock_in' => 'required',
            'clock_out' => 'nullable|after:clock_in',
            'notes' => 'nullable|string',
        ]);

        // Verifică dacă există o altă prezență pentru același angajat și aceeași dată
        $existingAttendance = Attendance::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->where('id', '!=', $attendance->id)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()
                ->with('error', 'Există deja o înregistrare pentru acest angajat în această dată!')
                ->withInput();
        }

        $attendance->update([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'notes' => $request->notes,
        ]);

        return redirect()->route('attendances.index')
            ->with('success', 'Prezența a fost actualizată cu succes!');
    }

    public function destroy(Attendance $attendance)
    {
        $user = request()->user();
        // Doar Admin și HR pot șterge prezențele
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('attendances.index')
                ->with('error', 'Nu ai permisiunea să efectuezi această acțiune!');
        }

        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Prezența a fost ștearsă cu succes!');
    }

    public function clockIn()
    {
        $user = request()->user();
        $today = now()->toDateString();

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => $today,
        ]);

        if (!$attendance->clock_in) {
            $attendance->clock_in = now()->toTimeString();
            $attendance->save();

            return redirect()->back()->with('success', 'Ai fost pontat cu succes!');
        }

        return redirect()->back()->with('error', 'Ești deja pontat pentru ziua de azi!');
    }

    public function clockOut()
    {
        $user = request()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
                               ->where('date', $today)
                               ->first();

        if ($attendance && $attendance->clock_in && !$attendance->clock_out) {
            $attendance->clock_out = now()->toTimeString();
            $attendance->save();

            return redirect()->back()->with('success', 'Ai fost pontat la ieșire cu succes!');
        }

        return redirect()->back()->with('error', 'Nu poți ponta ieșirea fără o intrare validă!');
    }
    // public function clockIn()
    // {
    //     $user = request()->user();

    //     if (!$user) {
    //         return redirect()->back()->with('error', 'Utilizator neautentificat!');
    //     }

    //     $today = now()->toDateString();

    //     // Verifică dacă utilizatorul are deja o înregistrare pentru astăzi
    //     $attendance = Attendance::where('user_id', $user->id)
    //         ->where('date', $today)
    //         ->first();

    //     if ($attendance) {
    //         // Dacă utilizatorul are deja o înregistrare pentru astăzi
    //         if ($attendance->clock_in && !$attendance->clock_out) {
    //             // Utilizatorul este deja pontat, dar nu a ieșit încă
    //             return redirect()->back()->with('error', 'Ești deja pontat pentru ziua de azi!');
    //         } elseif ($attendance->clock_in && $attendance->clock_out) {
    //             // Utilizatorul a făcut deja check-in și check-out astăzi
    //             return redirect()->back()->with('error', 'Ai fost deja pontat pentru întreaga zi de astăzi!');
    //         }
    //     } else {
    //         // Creează o nouă înregistrare de prezență
    //         $attendance = new Attendance([
    //             'user_id' => $user->id,
    //             'date' => $today,
    //         ]);
    //     }

    //     $attendance->clock_in = now()->toTimeString();
    //     $attendance->save();

    //     return redirect()->back()->with('success', 'Ai fost pontat cu succes!');
    // }

    // public function clockOut()
    // {
    //     $user = request()->user();

    //     if (!$user) {
    //         return redirect()->back()->with('error', 'Utilizator neautentificat!');
    //     }

    //     $today = now()->toDateString();

    //     $attendance = Attendance::where('user_id', $user->id)
    //         ->where('date', $today)
    //         ->first();

    //     if (!$attendance) {
    //         return redirect()->back()->with('error', 'Nu ai fost pontat la intrare astăzi!');
    //     }

    //     if (!$attendance->clock_in) {
    //         return redirect()->back()->with('error', 'Nu poți ponta ieșirea fără o intrare validă!');
    //     }

    //     if ($attendance->clock_out) {
    //         return redirect()->back()->with('error', 'Ai fost deja pontat la ieșire astăzi!');
    //     }

    //     $attendance->clock_out = now()->toTimeString();
    //     $attendance->save();

    //     return redirect()->back()->with('success', 'Ai fost pontat la ieșire cu succes!');
    // }

    public function report()
    {
        $user = request()->user();
        // Doar Admin și HR pot vedea rapoarte
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('attendances.index')
                ->with('error', 'Nu ai permisiunea să accesezi această pagină!');
        }

        $users = User::all();
        $startDate = request('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = request('end_date', Carbon::now()->endOfMonth()->toDateString());
        $selectedUser = request('user_id');

        $query = Attendance::whereBetween('date', [$startDate, $endDate]);

        if ($selectedUser) {
            $query->where('user_id', $selectedUser);
        }

        $attendances = $query->orderBy('date')->get();

        // Calculează statistici
        $statistics = [];
        foreach ($users as $user) {
            $userAttendances = $attendances->where('user_id', $user->id);

            if ($userAttendances->count() > 0) {
                $totalHours = 0;
                $daysPresent = 0;

                foreach ($userAttendances as $attendance) {
                    if ($attendance->clock_in && $attendance->clock_out) {
                        $start = Carbon::parse($attendance->date . ' ' . $attendance->clock_in);
                        $end = Carbon::parse($attendance->date . ' ' . $attendance->clock_out);
                        $totalHours += $end->diffInHours($start);
                        $daysPresent++;
                    }
                }

                $statistics[$user->id] = [
                    'name' => $user->name,
                    'days_present' => $daysPresent,
                    'total_hours' => $totalHours,
                    'average_hours' => $daysPresent > 0 ? round($totalHours / $daysPresent, 1) : 0,
                ];
            }
        }

        return view('attendances.report', compact('users', 'attendances', 'statistics', 'startDate', 'endDate', 'selectedUser'));
    }
}
