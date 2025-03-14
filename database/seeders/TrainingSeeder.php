<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/TrainingSeeder.php
public function run()
{
    $trainings = [
        [
            'title' => 'Introducere în Laravel',
            'description' => 'Curs de bază pentru dezvoltarea aplicațiilor web cu Laravel.',
            'link' => 'https://laracasts.com',
        ],
        [
            'title' => 'Management eficient al timpului',
            'description' => 'Învață cum să-ți gestionezi mai bine timpul și să-ți crești productivitatea.',
            'link' => 'https://example.com/time-management',
        ],
        [
            'title' => 'Leadership și comunicare',
            'description' => 'Dezvoltă-ți abilitățile de leadership și comunicare eficientă în echipă.',
            'link' => 'https://example.com/leadership',
        ],
    ];

    foreach ($trainings as $training) {
        DB::table('trainings')->insert([
            'title' => $training['title'],
            'description' => $training['description'],
            'link' => $training['link'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
}
