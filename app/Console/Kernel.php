<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\PostController;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    // protected function schedule(Schedule $schedule)
    // {
    //     // Rulează o dată pe zi la miezul nopții
    //     $schedule->call(function () {
    //         $controller = new PostController();
    //         $controller->deleteInactive();
    //     })->daily();
    // }
    protected $routeMiddleware = [
        // alte middleware-uri...
        'role' => \App\Http\Middleware\CheckRole::class,
    ];
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    // app/Console/Kernel.php
    protected function schedule(Schedule $schedule)
    {
        // Rulează o dată pe zi la miezul nopții
        $schedule->call(function () {
            $controller = new \App\Http\Controllers\PostController();
            $controller->deleteInactive();
        })->daily();
    }
}