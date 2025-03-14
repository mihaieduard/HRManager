<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        // Verifică dacă utilizatorul are permisiunile necesare
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        return view('settings.index');
    }

    public function update(Request $request)
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        // Aici implementezi logica de actualizare a setărilor
        // De exemplu, actualizează setări de companie, preferințe de sistem, etc.
        
        return redirect()->route('settings.index')->with('success', 'Setările au fost actualizate cu succes!');
    }

    public function users()
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin() && !$user->isHR()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $users = User::with('role')->paginate(10);
        return view('settings.users', compact('users'));
    }

    public function roles()
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        $roles = Role::all();
        return view('settings.roles', compact('roles'));
    }

    public function backup()
    {
        // Verifică dacă utilizatorul are permisiunile necesare
        $userId = request()->user()->id ?? null;
        $user = request()->user();
        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Nu aveți permisiunea să accesați această pagină.');
        }
        
        return view('settings.backup');
    }
}