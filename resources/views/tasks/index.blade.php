@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Managementul Task-urilor</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            <i class="bi bi-plus-lg me-1"></i> Task Nou
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="d-flex p-3 border-bottom">
                        <div class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control border-0 bg-light" placeholder="Caută task-uri...">
                            </div>
                        </div>
                        <div class="ms-3">
                            <select class="form-select bg-light border-0" id="filterStatus">
                                <option value="">Toate statusurile</option>
                                <option value="todo">De făcut</option>
                                <option value="in_progress">În progres</option>
                                <option value="review">În revizuire</option>
                                <option value="completed">Finalizate</option>
                            </select>
                        </div>
                        <div class="ms-3">
                            <select class="form-select bg-light border-0" id="filterPriority">
                                <option value="">Toate prioritățile</option>
                                <option value="low">Scăzută</option>
                                <option value="medium">Medie</option>
                                <option value="high">Ridicată</option>
                            </select>
                        </div>
                        <div class="ms-3">
                            <select class="form-select bg-light border-0" id="filterAssigned">
                                <option value="">Toți utilizatorii</option>
                                <option value="me">Asignate mie</option>
                                <option value="created">Create de mine</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;"></th>
                                    <th scope="col">Task</th>
                                    <th scope="col">Asignat</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Prioritate</th>
                                    <th scope="col">Deadline</th>
                                    <th scope="col" style="width: 120px;">Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i <= 10; $i++)
                                    @php
                                        // Sample data for demonstration
                                        $statuses = [
                                            'todo' => ['De făcut', 'bg-secondary'],
                                            'in_progress' => ['În progres', 'bg-info'],
                                            'review' => ['În revizuire', 'bg-primary'],
                                            'completed' => ['Finalizat', 'bg-success']
                                        ];
                                        $randomStatus = array_rand($statuses);
                                        
                                        $priorities = [
                                            'low' => ['Scăzută', 'bg-success'],
                                            'medium' => ['Medie', 'bg-warning'],
                                            'high' => ['Ridicată', 'bg-danger']
                                        ];
                                        $randomPriority = array_rand($priorities);
                                        
                                        $daysToAdd = rand(-10, 30);
                                        $deadline = now()->addDays($daysToAdd);
                                        $isOverdue = $daysToAdd < 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="task{{ $i }}Check" 
                                                    {{ $randomStatus == 'completed' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="task{{ $i }}Check"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium mb-1">Task de exemplu #{{ $i }}</div>
                                            <div class="text-muted small">Aceasta este o descriere scurtă a taskului care include detalii relevante...</div>
                                            @if(rand(0, 1))
                                                <div class="mt-1">
                                                    <span class="badge bg-light text-dark">frontend</span>
                                                    <span class="badge bg-light text-dark">development</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background=random" alt="User" class="avatar me-2">
                                                <div>
                                                    <div class="fw-medium">Utilizator {{ $i }}</div>
                                                    <div class="small text-muted">Departament Test</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $statuses[$randomStatus][1] }}">
                                                {{ $statuses[$randomStatus][0] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $priorities[$randomPriority][1] }}">
                                                {{ $priorities[$randomPriority][0] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="{{ $isOverdue ? 'text-danger fw-medium' : '' }}">
                                                {{ $deadline->format('d M Y') }}
                                                @if($isOverdue)
                                                    <i class="bi bi-exclamation-circle-fill ms-1" title="Întârziat"></i>
                                                @endif
                                            </div>
                                            <div class="small text-muted">
                                                @if($isOverdue)
                                                    Întârziat cu {{ abs($daysToAdd) }} zile
                                                @else
                                                    {{ $daysToAdd }} zile rămase
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewTaskModal{{ $i }}"><i class="bi bi-eye me-2"></i>Vezi detalii</a></li>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $i }}"><i class="bi bi-pencil me-2"></i>Editează</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Șterge</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <div>Afișare 1-10 din 50 rezultate</div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Următor</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Task-uri pe Statusuri</h5>
                </div>
                <div class="card-body">
                    <canvas id="tasksChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribuție Prioritate</h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Create Task -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Adaugă Task Nou</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Titlu</label>
                        <input type="text" class="form-control" id="taskTitle" placeholder="Titlul taskului">
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Descriere</label>
                        <textarea class="form-control" id="taskDescription" rows="3" placeholder="Detaliați taskul..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskAssignee" class="form-label">Asignat către</label>
                                <select class="form-select" id="taskAssignee">
                                    <option value="">Selectează angajat</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">Utilizator {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskStatus" class="form-label">Status</label>
                                <select class="form-select" id="taskStatus">
                                    <option value="todo">De făcut</option>
                                    <option value="in_progress">În progres</option>
                                    <option value="review">În revizuire</option>
                                    <option value="completed">Finalizat</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskPriority" class="form-label">Prioritate</label>
                                <select class="form-select" id="taskPriority">
                                    <option value="low">Scăzută</option>
                                    <option value="medium" selected>Medie</option>
                                    <option value="high">Ridicată</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskDeadline" class="form-label">Deadline</label>
                                <input type="date" class="form-control" id="taskDeadline" value="{{ now()->addDays(7)->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="taskTags" class="form-label">Tag-uri</label>
                        <input type="text" class="form-control" id="taskTags" placeholder="Introduceți tag-uri separate prin virgulă">
                        <div class="form-text">Exemplu: frontend, raport, urgent</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                <button type="button" class="btn btn-primary">Salvează Task</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tasks by Status Chart
        const tasksCtx = document.getElementById('tasksChart').getContext('2d');
        const tasksChart = new Chart(tasksCtx, {
            type: 'bar',
            data: {
                labels: ['De făcut', 'În progres', 'În revizuire', 'Finalizat'],
                datasets: [{
                    label: 'Număr de task-uri',
                    data: [12, 19, 8, 15],
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.6)',
                        'rgba(13, 202, 240, 0.6)',
                        'rgba(13, 110, 253, 0.6)',
                        'rgba(25, 135, 84, 0.6)'
                    ],
                    borderColor: [
                        'rgb(108, 117, 125)',
                        'rgb(13, 202, 240)',
                        'rgb(13, 110, 253)',
                        'rgb(25, 135, 84)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Priority Distribution Chart
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        const priorityChart = new Chart(priorityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Scăzută', 'Medie', 'Ridicată'],
                datasets: [{
                    data: [15, 25, 10],
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.6)',
                        'rgba(255, 193, 7, 0.6)',
                        'rgba(220, 53, 69, 0.6)'
                    ],
                    borderColor: [
                        'rgb(25, 135, 84)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection