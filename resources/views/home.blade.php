@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Dashboard</h2>
        <div>
            <span class="badge bg-light text-dark p-2">
                <i class="bi bi-calendar3"></i> {{ now()->format('d F Y') }}
            </span>
        </div>
    </div>

    <div class="row dashboard-stats">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Angajați Activi</h6>
                            <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="bi bi-people text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-success fw-medium">
                            <i class="bi bi-arrow-up"></i> 4% 
                        </span>
                        <span class="text-muted">față de luna trecută</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Prezenți Azi</h6>
                            <h3 class="mb-0">{{ \App\Models\Attendance::where('date', now()->toDateString())->count() }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="bi bi-calendar-check text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            @php
                                $totalUsers = \App\Models\User::count();
                                $presentToday = \App\Models\Attendance::where('date', now()->toDateString())->count();
                                $percentage = $totalUsers > 0 ? round(($presentToday / $totalUsers) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" 
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">{{ $percentage }}% din total angajați</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Taskuri Deschise</h6>
                            <h3 class="mb-0">{{ rand(5, 20) }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="bi bi-check2-square text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-warning text-dark me-1">{{ rand(2, 8) }} prioritare</span>
                        <span class="badge bg-info text-white">{{ rand(3, 10) }} în progres</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Evaluări Programate</h6>
                            <h3 class="mb-0">{{ rand(1, 10) }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="bi bi-graph-up text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted">Următoarea: {{ now()->addDays(rand(1, 14))->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Taskuri Recente</span>
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary">Vezi toate</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Asignat</th>
                                    <th>Prioritate</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 5; $i++)
                                    @php
                                        $priorities = ['Scăzută', 'Medie', 'Ridicată'];
                                        $priorityColors = ['success', 'warning', 'danger'];
                                        $priorityIndex = array_rand($priorities);
                                        
                                        $statuses = ['În așteptare', 'În progres', 'Revizuire', 'Finalizat'];
                                        $statusColors = ['secondary', 'info', 'primary', 'success'];
                                        $statusIndex = array_rand($statuses);
                                        
                                        $days = rand(-10, 30);
                                        $deadlineDate = now()->addDays($days);
                                        $isLate = $days < 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-medium">Task de testare #{{ $i + 1 }}</span>
                                            <div class="text-muted small">Descriere scurtă a taskului...</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=Test+User&background=random" class="avatar me-2">
                                                <span>Utilizator Test</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $priorityColors[$priorityIndex] }}">
                                                {{ $priorities[$priorityIndex] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $statusColors[$statusIndex] }}">
                                                {{ $statuses[$statusIndex] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $isLate ? 'text-danger fw-bold' : '' }}">
                                                {{ $deadlineDate->format('d M Y') }}
                                                @if($isLate)
                                                    <i class="bi bi-exclamation-circle-fill ms-1"></i>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    Calendar Evenimente
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @for ($i = 0; $i < 4; $i++)
                            @php
                                $days = rand(0, 30);
                                $eventDate = now()->addDays($days);
                                $eventTypes = [
                                    ['Întâlnire echipă', 'bi-people', 'bg-primary'],
                                    ['Training', 'bi-mortarboard', 'bg-success'],
                                    ['Evaluare', 'bi-graph-up', 'bg-info'],
                                    ['Deadline proiect', 'bi-calendar-check', 'bg-warning']
                                ];
                                $event = $eventTypes[array_rand($eventTypes)];
                            @endphp
                            <div class="list-group-item p-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <div class="{{ $event[2] }} text-white d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px;">
                                            <i class="bi {{ $event[1] }} fs-5"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $event[0] }}</h6>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">
                                                <i class="bi bi-calendar me-1"></i> {{ $eventDate->format('d M Y') }}
                                            </span>
                                            <span class="text-muted">
                                                <i class="bi bi-clock me-1"></i> {{ rand(8, 17) }}:00
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="#" class="text-decoration-none">Vezi toate evenimentele</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    Training-uri Recomandate
                </div>
                <div class="card-body">
                    @for ($i = 0; $i < 3; $i++)
                        @php
                            $trainingTypes = [
                                ['Leadership & Management', 'bg-primary'],
                                ['Dezvoltare Personală', 'bg-success'],
                                ['Competențe Tehnice', 'bg-info'],
                                ['Comunicare Eficientă', 'bg-warning']
                            ];
                            $training = $trainingTypes[array_rand($trainingTypes)];
                            $progress = rand(0, 100);
                        @endphp
                        <div class="mb-3 {{ $i < 2 ? 'border-bottom pb-3' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">{{ $training[0] }}</span>
                                <span class="badge {{ $training[1] }}">{{ rand(1, 5) }} module</span>
                            </div>
                            <div class="progress mb-2" style="height: 5px;">
                                <div class="progress-bar {{ $training[1] }}" role="progressbar" style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Progres: {{ $progress }}%</small>
                                <a href="{{ route('trainings.index') }}" class="small text-decoration-none">Continuă</a>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection