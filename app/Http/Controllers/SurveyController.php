<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use Carbon\Carbon;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Doar admin și HR pot crea, edita și șterge sondaje
        $this->middleware(['role:Admin,HR'])->except(['index', 'show', 'vote']);
    }
    
    public function index()
    {
        $surveys = Survey::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('surveys.index', compact('surveys'));
    }
    
    public function create()
    {
        return view('surveys.create');
    }
    
    public function store(Request $request)
    {   
        $userId = request()->user()->id ?? null;
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'end_date' => 'nullable|date|after:today',
        ]);
        
        // Inițializează rezultatele ca un array gol pentru fiecare opțiune
        $results = [];
        foreach ($request->options as $index => $option) {
            $results[$index] = 0;
        }
        
        Survey::create([
            'user_id' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'options' => $request->options,
            'results' => $results,
            'end_date' => $request->end_date,
        ]);
        
        return redirect()->route('surveys.index')
                         ->with('success', 'Sondajul a fost creat cu succes!');
    }
    
    public function show(Survey $survey)
    {
        // Verifică dacă sondajul a expirat
        $expired = $survey->end_date && Carbon::parse($survey->end_date)->isPast();
        
        // Verifică dacă utilizatorul curent a votat deja
        $userVoted = session()->has('survey_' . $survey->id . '_voted');
        
        return view('surveys.show', compact('survey', 'expired', 'userVoted'));
    }
    
    public function edit(Survey $survey)
    {
        return view('surveys.edit', compact('survey'));
    }
    
    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'end_date' => 'nullable|date|after:today',
        ]);
        
        // Verifică dacă au fost modificate opțiunile
        $optionsChanged = $request->options != $survey->options;
        
        // Dacă opțiunile au fost modificate, resetează rezultatele
        if ($optionsChanged) {
            $results = [];
            foreach ($request->options as $index => $option) {
                $results[$index] = 0;
            }
        } else {
            $results = $survey->results;
        }
        
        $survey->update([
            'title' => $request->title,
            'description' => $request->description,
            'options' => $request->options,
            'results' => $results,
            'end_date' => $request->end_date,
        ]);
        
        return redirect()->route('surveys.index')
                         ->with('success', 'Sondajul a fost actualizat cu succes!' . 
                                   ($optionsChanged ? ' Rezultatele au fost resetate deoarece opțiunile au fost modificate.' : ''));
    }
    
    public function destroy(Survey $survey)
    {
        $survey->delete();
        
        return redirect()->route('surveys.index')
                         ->with('success', 'Sondajul a fost șters cu succes!');
    }
    
    public function vote(Request $request, Survey $survey)
    {
        $request->validate([
            'option' => 'required|integer|min:0',
        ]);
        
        // Verifică dacă sondajul a expirat
        if ($survey->end_date && Carbon::parse($survey->end_date)->isPast()) {
            return redirect()->back()->with('error', 'Acest sondaj a expirat. Nu mai poți vota.');
        }
        
        // Verifică dacă utilizatorul a votat deja
        if (session()->has('survey_' . $survey->id . '_voted')) {
            return redirect()->back()->with('error', 'Ai votat deja în acest sondaj.');
        }
        
        // Verifică dacă opțiunea este validă
        if (!isset($survey->options[$request->option])) {
            return redirect()->back()->with('error', 'Opțiunea selectată nu este validă.');
        }
        
        // Actualizează rezultatele
        $results = $survey->results;
        $results[$request->option]++;
        
        $survey->update([
            'results' => $results,
        ]);
        
        // Marchează utilizatorul ca având votat
        session()->put('survey_' . $survey->id . '_voted', true);
        
        return redirect()->back()->with('success', 'Votul tău a fost înregistrat cu succes!');
    }
}