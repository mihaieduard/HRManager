<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotQuestion;

// class ChatbotController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth');
//         // Doar admin și HR pot gestiona întrebările chatbot-ului
//         $this->middleware(['role:Admin,HR'])->only(['manage', 'storeQuestion', 'updateQuestion', 'destroyQuestion']);
//     }
    
//     public function index()
//     {
//         return view('chatbot.index');
//     }
    
//     public function ask(Request $request)
//     {
//         $request->validate([
//             'question' => 'required|string',
//         ]);
        
//         $userQuestion = strtolower($request->question);
        
//         // Obține toate întrebările din baza de date
//         $chatbotQuestions = ChatbotQuestion::all();
        
//         foreach ($chatbotQuestions as $q) {
//             // Verificăm dacă întrebarea utilizatorului conține întrebarea predefinită
//             // Această abordare simplă poate fi îmbunătățită
//             if (strpos(strtolower($userQuestion), strtolower($q->question)) !== false ||
//                 similar_text(strtolower($userQuestion), strtolower($q->question)) > strlen($q->question) * 0.7) {
//                 return response()->json(['answer' => $q->answer]);
//             }
//         }
        
//         // Răspuns implicit dacă nu găsim o potrivire
//         return response()->json([
//             'answer' => 'Îmi pare rău, nu pot răspunde la această întrebare. Te rog să contactezi departamentul HR pentru asistență: hr@company.com sau la telefonul intern 123.'
//         ]);
//     }
    
//     public function manage()
//     {
//         $questions = ChatbotQuestion::all();
//         return view('chatbot.manage', compact('questions'));
//     }
    
//     public function storeQuestion(Request $request)
//     {
//         $request->validate([
//             'question' => 'required|string|max:255',
//             'answer' => 'required|string',
//         ]);
        
//         ChatbotQuestion::create($request->all());
        
//         return redirect()->route('chatbot.manage')
//                          ->with('success', 'Întrebarea a fost adăugată cu succes!');
//     }
    
//     public function updateQuestion(Request $request, ChatbotQuestion $question)
//     {
//         $request->validate([
//             'question' => 'required|string|max:255',
//             'answer' => 'required|string',
//         ]);
        
//         $question->update($request->all());
        
//         return redirect()->route('chatbot.manage')
//                          ->with('success', 'Întrebarea a fost actualizată cu succes!');
//     }
    
//     public function destroyQuestion(ChatbotQuestion $question)
//     {
//         $question->delete();
        
//         return redirect()->route('chatbot.manage')
//                          ->with('success', 'Întrebarea a fost ștearsă cu succes!');
//     }
// }

// app/Http/Controllers/ChatbotController.php
class ChatbotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('chatbot.index');
    }
    
    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
        ]);
        
        $userQuestion = strtolower($request->question);
        
        // Obține toate întrebările din baza de date
        $chatbotQuestions = ChatbotQuestion::all();
        
        foreach ($chatbotQuestions as $q) {
            // Verificăm dacă întrebarea utilizatorului conține întrebarea predefinită
            // Această abordare simplă poate fi îmbunătățită
            if (strpos(strtolower($userQuestion), strtolower($q->question)) !== false ||
                similar_text(strtolower($userQuestion), strtolower($q->question)) > strlen($q->question) * 0.7) {
                return response()->json(['answer' => $q->answer]);
            }
        }
        
        // Răspuns implicit dacă nu găsim o potrivire
        return response()->json([
            'answer' => 'Îmi pare rău, nu pot răspunde la această întrebare. Te rog să contactezi departamentul HR pentru asistență: hr@company.com sau la telefonul intern 123.'
        ]);
    }
    
    public function manage()
    {
        $questions = ChatbotQuestion::all();
        return view('chatbot.manage', compact('questions'));
    }
    
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        
        ChatbotQuestion::create($request->all());
        
        return redirect()->route('chatbot.manage')
                         ->with('success', 'Întrebarea a fost adăugată cu succes!');
    }
    
    public function updateQuestion(Request $request, ChatbotQuestion $question)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        
        $question->update($request->all());
        
        return redirect()->route('chatbot.manage')
                         ->with('success', 'Întrebarea a fost actualizată cu succes!');
    }
    
    public function destroyQuestion(ChatbotQuestion $question)
    {
        $question->delete();
        
        return redirect()->route('chatbot.manage')
                         ->with('success', 'Întrebarea a fost ștearsă cu succes!');
    }
}