<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatbotQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/ChatbotQuestionSeeder.php
public function run()
{
    $questions = [
        [
            'question' => 'Cum pot cere concediu?',
            'answer' => 'Pentru a cere concediu, trebuie să completezi formularul de concediu disponibil în secțiunea "Concedii" și să-l trimiți managerului tău cu cel puțin 2 săptămâni înainte.',
        ],
        [
            'question' => 'Care este programul de lucru?',
            'answer' => 'Programul standard de lucru este de luni până vineri, între orele 9:00 și 17:00, cu o pauză de masă de 1 oră.',
        ],
        [
            'question' => 'Cum pot vedea zilele mele de concediu rămase?',
            'answer' => 'Poți vedea zilele de concediu rămase în profilul tău, în secțiunea "Concedii".',
        ],
        [
            'question' => 'Cum pot contacta departamentul HR?',
            'answer' => 'Poți contacta departamentul HR la adresa de email hr@company.com sau la telefonul intern 123.',
        ],
        [
            'question' => 'Cum funcționează sistemul de evaluare a performanței?',
            'answer' => 'Sistemul de evaluare a performanței se bazează pe îndeplinirea task-urilor zilnice. La sfârșitul fiecărei luni, vei primi un punctaj total care reflectă performanța ta.',
        ],
    ];

    foreach ($questions as $q) {
        DB::table('chatbot_questions')->insert([
            'question' => $q['question'],
            'answer' => $q['answer'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
}
