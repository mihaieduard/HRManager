@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Rapoarte</h2>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="reportType" class="form-label">Tip Raport</label>
                            <select class="form-select" id="reportType">
                                <option value="">Selectează tip raport</option>
                                <option value="attendance">Pontaj</option>
                                <option value="performance">Evaluări</option>
                                <option value="tasks">Task-uri</option>
                                <option value="trainings">Training-uri</option>
                                <option value="surveys">Sondaje</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="startDate" class="form-label">De la</label>
                            <input type="date" class="form-control" id="startDate" value="{{ now()->subMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="endDate" class="form-label">Până la</label>
                            <input type="date" class="form-control" id="endDate" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="generateReport">
                                <i class="bi bi-search me-1"></i> Generează
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Rapoarte Rapide</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('reports.attendance') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-calendar-check me-2 text-primary"></i> Pontaj
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ rand(10, 50) }}</span>
                    </a>
                    <a href="{{ route('reports.performance') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-graph-up me-2 text-success"></i> Evaluări
                        </div>
                        <span class="badge bg-success rounded-pill">{{ rand(5, 20) }}</span>
                    </a>
                    <a href="{{ route('reports.tasks') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-check2-square me-2 text-info"></i> Task-uri
                        </div>
                        <span class="badge bg-info rounded-pill">{{ rand(20, 100) }}</span>
                    </a>
                    <a href="{{ route('reports.trainings') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-mortarboard me-2 text-warning"></i> Training-uri
                        </div>
                        <span class="badge bg-warning rounded-pill">{{ rand(5, 30) }}</span>
                    </a>
                    <a href="{{ route('reports.surveys') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-clipboard-data me-2 text-danger"></i> Sondaje
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ rand(2, 15) }}</span>
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Export</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.export') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exportReportType" class="form-label">Tip Raport</label>
                            <select class="form-select mb-2" id="exportReportType" name="report_type" required>
                                <option value="">Selectează tip raport</option>
                                <option value="attendance">Pontaj</option>
                                <option value="performance">Evaluări</option>
                                <option value="tasks">Task-uri</option>
                                <option value="trainings">Training-uri</option>
                                <option value="surveys">Sondaje</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Format</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="format" id="formatExcel" value="excel" checked>
                                    <label class="form-check-label" for="formatExcel">Excel</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="format" id="formatPdf" value="pdf">
                                    <label class="form-check-label" for="formatPdf">PDF</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="format" id="formatCsv" value="csv">
                                    <label class="form-check-label" for="formatCsv">CSV</label>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-download me-1"></i> Exportă
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statistici Generale</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Ultimele 30 de zile
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="periodDropdown">
                            <li><a class="dropdown-item" href="#">Ultima săptămână</a></li>
                            <li><a class="dropdown-item active" href="#">Ultimele 30 de zile</a></li>
                            <li><a class="dropdown-item" href="#">Ultimele 3 luni</a></li>
                            <li><a class="dropdown-item" href="#">Ultimele 6 luni</a></li>
                            <li><a class="dropdown-item" href="#">Ultimul an</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-light h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Rate Prezență</h6>
                                        <span class="badge bg-success">98%</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 98%" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="small text-muted">Creștere 2% față de perioada anterioară</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-light h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Satisfacție Angajați</h6>
                                        <span class="badge bg-primary">4.2/5</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 84%" aria-valuenow="84" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="small text-muted">Bazat pe 45 răspunsuri la sondaje</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-light h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Performanță</h6>
                                        <span class="badge bg-info">85%</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="small text-muted">Bazat pe evaluările din ultimele 30 de zile</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <canvas id="mainChart" height="300"></canvas>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Task-uri Finalizate</h6>
                            <canvas id="tasksChart" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Distribuție Training-uri</h6>
                            <canvas id="trainingsChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Main Chart - Attendance Over Time
        const mainCtx = document.getElementById('mainChart').getContext('2d');
        const mainChart = new Chart(mainCtx, {
            type: 'line',
            data: {
                labels: ['1 Mar', '5 Mar', '10 Mar', '15 Mar', '20 Mar', '25 Mar', '30 Mar'],
                datasets: [{
                    label: 'Prezență',
                    data: [45, 47, 46, 48, 47, 49, 50],
                    borderColor: 'rgb(25, 135, 84)',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Performanță',
                    data: [82, 83, 85, 84, 86, 85, 87],
                    borderColor: 'rgb(13, 110, 253)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
        
        // Tasks Chart
        const tasksCtx = document.getElementById('tasksChart').getContext('2d');
        const tasksChart = new Chart(tasksCtx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Taskuri finalizate',
                    data: [12, 19, 15, 20],
                    backgroundColor: 'rgba(13, 202, 240, 0.6)',
                    borderColor: 'rgb(13, 202, 240)',
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
        
        // Trainings Chart
        const trainingsCtx = document.getElementById('trainingsChart').getContext('2d');
        const trainingsChart = new Chart(trainingsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tehnic', 'Management', 'Dezvoltare Personală', 'Comunicare', 'Leadership'],
                datasets: [{
                    data: [12, 8, 15, 9, 6],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(25, 135, 84, 0.7)',
                        'rgba(13, 202, 240, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        'rgb(13, 110, 253)',
                        'rgb(25, 135, 84)',
                        'rgb(13, 202, 240)',
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
                        position: 'right'
                    }
                }
            }
        });
        
        // Report generation logic
        document.getElementById('generateReport').addEventListener('click', function() {
            const reportType = document.getElementById('reportType').value;
            if (!reportType) {
                alert('Vă rugăm selectați un tip de raport!');
                return;
            }
            
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            // Redirect to the specific report page with parameters
            window.location.href = `{{ url('/reports') }}/${reportType}?start_date=${startDate}&end_date=${endDate}`;
        });
    });
</script>
@endpush
@endsection