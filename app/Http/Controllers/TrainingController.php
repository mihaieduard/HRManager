<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Doar admin și HR pot crea, edita și șterge cursuri
        $this->middleware(['role:Admin,HR'])->except(['index', 'show']);
    }
    
    public function index()
    {
        $trainings = Training::orderBy('created_at', 'desc')->paginate(10);
        return view('trainings.index', compact('trainings'));
    }
    
    public function create()
    {
        return view('trainings.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);
        
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
        ];
        
        // Procesează fișierul atașat dacă există
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('trainings', 'public');
            $data['attachment'] = $path;
        }
        
        Training::create($data);
        
        return redirect()->route('trainings.index')
                         ->with('success', 'Cursul a fost adăugat cu succes!');
    }
    
    public function show(Training $training)
    {
        return view('trainings.show', compact('training'));
    }
    
    public function edit(Training $training)
    {
        return view('trainings.edit', compact('training'));
    }
    
    public function update(Request $request, Training $training)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);
        
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
        ];
        
        // Procesează fișierul atașat dacă există
        if ($request->hasFile('attachment')) {
            // Șterge fișierul vechi dacă există
            if ($training->attachment) {
                Storage::disk('public')->delete($training->attachment);
            }
            
            $path = $request->file('attachment')->store('trainings', 'public');
            $data['attachment'] = $path;
        }
        
        $training->update($data);
        
        return redirect()->route('trainings.index')
                         ->with('success', 'Cursul a fost actualizat cu succes!');
    }
    
    public function destroy(Training $training)
    {
        // Șterge fișierul atașat dacă există
        if ($training->attachment) {
            Storage::disk('public')->delete($training->attachment);
        }
        
        $training->delete();
        
        return redirect()->route('trainings.index')
                         ->with('success', 'Cursul a fost șters cu succes!');
    }
}