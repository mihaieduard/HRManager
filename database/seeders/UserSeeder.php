<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/UserSeeder.php
public function run()
{
    // Admin
    DB::table('users')->insert([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role_id' => 1, // Admin
        'position' => 'Administrator Sistem',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // HR
    DB::table('users')->insert([
        'name' => 'HR Manager',
        'email' => 'hr@example.com',
        'password' => Hash::make('password'),
        'role_id' => 2, // HR
        'position' => 'Manager HR',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Angajat
    DB::table('users')->insert([
        'name' => 'John Doe',
        'email' => 'employee@example.com',
        'password' => Hash::make('password'),
        'role_id' => 3, // Angajat
        'position' => 'Developer',
        'salary' => 5000,
        'contract_start' => '2022-01-01',
        'previous_experience' => 'Junior Developer - XYZ Company (2 ani)',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
}
