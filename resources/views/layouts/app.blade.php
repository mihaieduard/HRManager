<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HR Management') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --secondary-color: #64748B;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --info-color: #3B82F6;
            --light-color: #F3F4F6;
            --dark-color: #1F2937;
            --body-bg: #F9FAFB;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--dark-color);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .sidebar {
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px 0;
            background-color: var(--sidebar-bg);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        
        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: calc(100vh - 76px);
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: var(--secondary-color);
            padding: 10px 20px;
            margin: 2px 0;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }
        
        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background-color: #f3f4f8;
        }
        
        .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: #eef2ff;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                width: 100%;
                position: relative;
                top: 0;
                padding-top: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            background-color: var(--card-bg);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 15px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
            color: white;
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table th {
            background-color: #F9FAFB;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
        }
        
        .table td, .table th {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }
        
        .progress {
            height: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .avatar-group .avatar {
            margin-left: -10px;
            border: 2px solid white;
        }
        
        .avatar-group .avatar:first-child {
            margin-left: 0;
        }
        
        .navbar {
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            background-color: white;
        }
        
        .dashboard-stats .card {
            border-left: 4px solid transparent;
            transition: transform 0.2s ease-in-out;
        }
        
        .dashboard-stats .card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-stats .card.border-primary {
            border-left-color: var(--primary-color);
        }
        
        .dashboard-stats .card.border-success {
            border-left-color: var(--success-color);
        }
        
        .dashboard-stats .card.border-warning {
            border-left-color: var(--warning-color);
        }
        
        .dashboard-stats .card.border-info {
            border-left-color: var(--info-color);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }
        
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        
        .status-indicator.active {
            background-color: var(--success-color);
        }
        
        .status-indicator.inactive {
            background-color: var(--danger-color);
        }
        
        .status-indicator.pending {
            background-color: var(--warning-color);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-buildings me-2"></i>{{ config('app.name', 'HR Management') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-bell me-1"></i>
                                    <span class="badge bg-danger rounded-pill">3</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <h6 class="dropdown-header">Notificări</h6>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-calendar-check text-primary"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0 fw-medium">Întâlnire nouă programată</p>
                                                <small class="text-muted">Acum 5 minute</small>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-clipboard-check text-success"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0 fw-medium">Task nou asignat</p>
                                                <small class="text-muted">Acum 2 ore</small>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-trophy text-warning"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0 fw-medium">Training nou disponibil</p>
                                                <small class="text-muted">Acum 1 zi</small>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-center" href="#">Vezi toate notificările</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>{{ __('Profil') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @auth
        <div class="container-fluid">
            <div class="row">
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="bi bi-house-door"></i>
                                    Dashboard
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('employees*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                                    <i class="bi bi-people"></i>
                                    Angajați
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('attendance*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                    <i class="bi bi-calendar-check"></i>
                                    Pontaj
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('tasks*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                                    <i class="bi bi-check2-square"></i>
                                    Taskuri
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('trainings*') ? 'active' : '' }}" href="{{ route('trainings.index') }}">
                                    <i class="bi bi-mortarboard"></i>
                                    Training-uri
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('performance*') ? 'active' : '' }}" href="{{ route('performance.index') }}">
                                    <i class="bi bi-graph-up"></i>
                                    Evaluări
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('collaboration*') ? 'active' : '' }}" href="{{ route('collaboration.index') }}">
                                    <i class="bi bi-people-fill"></i>
                                    Spațiu Colaborativ
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('surveys*') ? 'active' : '' }}" href="{{ route('surveys.index') }}">
                                    <i class="bi bi-clipboard-data"></i>
                                    Sondaje
                                </a>
                            </li>
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isHR())
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                    Rapoarte
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                                    <i class="bi bi-gear"></i>
                                    Setări
                                </a>
                            </li>
                            @endif
                        </ul>
                        
                        @php
                            $user = auth()->user() ?? request()->user();
                            $today = now()->toDateString();
                            $todayAttendance = \App\Models\Attendance::where('user_id', $user->id)
                                                            ->where('date', $today)
                                                            ->first();
                            $canCheckIn = !$todayAttendance || (!$todayAttendance->clock_in && !$todayAttendance->clock_out);
                            $canCheckOut = $todayAttendance && $todayAttendance->clock_in && !$todayAttendance->clock_out;
                            $fullyChecked = $todayAttendance && $todayAttendance->clock_in && $todayAttendance->clock_out;
                        @endphp
                        
                        <div class="card mx-3 mt-4">
                            <div class="card-body">
                                <h6 class="mb-3">Pontaj Zilnic</h6>
                                
                                @if($fullyChecked)
                                    <div class="alert alert-success p-2 mb-0 text-center">
                                        <small>
                                            <i class="bi bi-check-circle me-1"></i> Pontat pentru azi!<br>
                                            <strong>Intrare:</strong> {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}<br>
                                            <strong>Ieșire:</strong> {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}
                                        </small>
                                    </div>
                                @elseif($canCheckIn)
                                    <form action="{{ route('attendance.clock-in') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> Pontează intrare
                                        </button>
                                    </form>
                                @elseif($canCheckOut)
                                    <div class="alert alert-info p-2 mb-2 text-center">
                                        <small>
                                            <i class="bi bi-info-circle me-1"></i> Pontat la intrare: {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}
                                        </small>
                                    </div>
                                    <form action="{{ route('attendance.clock-out') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="bi bi-box-arrow-left me-1"></i> Pontează ieșire
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                    @yield('content')
                </main>
            </div>
        </div>
        @else
            <main class="py-4">
                @yield('content')
            </main>
        @endauth
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    @stack('scripts')
</body>
</html>