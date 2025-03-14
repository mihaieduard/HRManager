<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\CollaborationController; // Ensure this class exists in the specified namespace
use App\Http\Controllers\DashboardController; // Ensure this class exists in the specified namespace
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ChatbotController; // Ensure this class exists in the specified namespace
use App\Http\Controllers\ReportControlle; // Ensure this class exists in the specified namespace
use App\Http\Controllers\SettingsController; // Ensure this class exists in the specified namespace
use Illuminate\Support\Facades\Auth;

// Main Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Employee Management
// Route::resource('employees', EmployeeController::class);
Route::middleware(['auth', 'role:Admin,HR'])->group(function () {
    Route::resource('employees', EmployeeController::class);
});

// Attendance Management
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

// Performance Reviews
Route::group(['prefix' => 'performance', 'as' => 'performance.'], function () {
    Route::get('/', [PerformanceReviewController::class, 'index'])->name('index');
    Route::get('/create', [PerformanceReviewController::class, 'create'])->name('create');
    Route::post('/', [PerformanceReviewController::class, 'store'])->name('store');
    Route::get('/{performance}', [PerformanceReviewController::class, 'show'])->name('show');
    Route::get('/{performance}/edit', [PerformanceReviewController::class, 'edit'])->name('edit');
    Route::put('/{performance}', [PerformanceReviewController::class, 'update'])->name('update');
    Route::delete('/{performance}', [PerformanceReviewController::class, 'destroy'])->name('destroy');
    Route::post('/{performance}/submit', [PerformanceReviewController::class, 'submit'])->name('submit');
    Route::post('/{performance}/acknowledge', [PerformanceReviewController::class, 'acknowledge'])->name('acknowledge');
    Route::get('/report/view', [PerformanceReviewController::class, 'report'])->name('report');
});
// Chatbot
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
// Tasks Management
Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::get('/create', [TaskController::class, 'create'])->name('create');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('show');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    Route::post('/{task}/status', [TaskController::class, 'updateStatus'])->name('status');
    Route::post('/{task}/comment', [TaskController::class, 'addComment'])->name('comment');
});

// Training Management
Route::group(['prefix' => 'trainings', 'as' => 'trainings.'], function () {
    Route::get('/', [TrainingController::class, 'index'])->name('index');
    Route::get('/create', [TrainingController::class, 'create'])->name('create');
    Route::post('/', [TrainingController::class, 'store'])->name('store');
    Route::get('/{training}', [TrainingController::class, 'show'])->name('show');
    Route::get('/{training}/edit', [TrainingController::class, 'edit'])->name('edit');
    Route::put('/{training}', [TrainingController::class, 'update'])->name('update');
    Route::delete('/{training}', [TrainingController::class, 'destroy'])->name('destroy');
    Route::post('/{training}/enroll', [TrainingController::class, 'enroll'])->name('enroll');
    Route::post('/{training}/progress', [TrainingController::class, 'updateProgress'])->name('progress');
    Route::get('/report/view', [TrainingController::class, 'report'])->name('report');
});

// Collaboration Space
Route::group(['prefix' => 'collaboration', 'as' => 'collaboration.'], function () {
    Route::get('/', [CollaborationController::class, 'index'])->name('index');
    Route::post('/post', [CollaborationController::class, 'storePost'])->name('post.store');
    Route::delete('/post/{post}', [CollaborationController::class, 'destroyPost'])->name('post.destroy');
    Route::post('/post/{post}/like', [CollaborationController::class, 'likePost'])->name('post.like');
    Route::post('/post/{post}/comment', [CollaborationController::class, 'commentPost'])->name('post.comment');
    Route::get('/events', [CollaborationController::class, 'events'])->name('events');
    Route::post('/events', [CollaborationController::class, 'storeEvent'])->name('events.store');
});

// Surveys Management
Route::group(['prefix' => 'surveys', 'as' => 'surveys.'], function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/create', [SurveyController::class, 'create'])->name('create');
    Route::post('/', [SurveyController::class, 'store'])->name('store');
    Route::get('/{survey}', [SurveyController::class, 'show'])->name('show');
    Route::get('/{survey}/edit', [SurveyController::class, 'edit'])->name('edit');
    Route::put('/{survey}', [SurveyController::class, 'update'])->name('update');
    Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('destroy');
    Route::post('/{survey}/publish', [SurveyController::class, 'publish'])->name('publish');
    Route::post('/{survey}/respond', [SurveyController::class, 'respond'])->name('respond');
    Route::get('/{survey}/results', [SurveyController::class, 'results'])->name('results');
});

// Reports
Route::group(['prefix' => 'reports', 'as' => 'reports.', 'middleware' => ['auth']], function () {
    Route::get('/', [ReportControlle::class, 'index'])->name('index');
    Route::get('/attendance', [ReportControlle::class, 'attendance'])->name('attendance');
    Route::get('/performance', [ReportControlle::class, 'performance'])->name('performance');
    Route::get('/tasks', [ReportControlle::class, 'tasks'])->name('tasks');
    Route::get('/trainings', [ReportControlle::class, 'trainings'])->name('trainings');
    Route::get('/surveys', [ReportControlle::class, 'surveys'])->name('surveys');
    Route::post('/export', [ReportControlle::class, 'export'])->name('export');
});

// Settings
Route::group(['prefix' => 'settings', 'as' => 'settings.', 'middleware' => ['auth']], function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::post('/update', [SettingsController::class, 'update'])->name('update');
    Route::get('/users', [SettingsController::class, 'users'])->name('users');
    Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
    Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
});

// User profile
Route::get('/profile', [HomeController::class, 'profile'])->name('profile.edit');
Route::post('/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/password', [HomeController::class, 'updatePassword'])->name('profile.password');