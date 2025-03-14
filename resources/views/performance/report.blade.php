@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Raport evaluări de performanță</div>

                <div class="card-body">
                    <form method="GET" action="{{ route('performance.report') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="user_id" class="form-label">Angajat</label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="">Toți angajații</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $selectedEmployee == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="year" class="form-label">Anul</label>
                                <select class="form-select" id="year" name="year">
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Filtrează</button>
                            </div>
                        </div>
                    </form>

                    <h5>Statistici evaluări pentru {{ $selectedYear }}</h5>
                    
                    @if(count($statistics) > 0)
                        <div class="table-responsive mb-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Angajat</th>
                                        <th>Evaluări</th>
                                        <th>Tehnic</th>
                                        <th>Comunicare</th>
                                        <th>Echipă</th>
                                        <th>Inițiativă</th>
                                        <th>Fiabilitate</th>
                                        <th>General</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statistics as $userId => $stat)
                                        <tr>
                                            <td>{{ $stat['name'] }}</td>
                                            <td>{{ $stat['reviews_count'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $stat['avg_technical'] >= 4 ? 'success' : ($stat['avg_technical'] >= 3 ? 'info' : 'warning') }}">
                                                    {{ $stat['avg_technical'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $stat['avg_communication'] >= 4 ? 'success' : ($stat['avg_communication'] >= 3 ? 'info' : 'warning') }}">
                                                    {{ $stat['avg_communication'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $stat['avg_teamwork'] >= 4 ? 'success' : ($stat['avg_teamwork'] >= 3 ? 'info' : 'warning') }}">
                                                    {{ $stat['avg_teamwork'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $stat['avg_initiative'] >= 4 ? 'success' : ($stat['avg_initiative'] >= 3 ? 'info' : 'warning') }}">
                                                    {{ $stat['avg_initiative'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $stat['avg_reliability'] >= 4 ? 'success' : ($stat['avg_reliability'] >= 3 ? 'info' : 'warning') }}">
                                                    {{ $stat['avg_reliability'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $stat['avg_overall'] >= 4 ? 'success' : ($stat['avg_overall'] >= 3 ? 'info' : 'warning') }}">
                                                    {{ $stat['avg_overall'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h5>Grafic comparativ</h5>
                        <div class="card mb-4">
                            <div class="card-body">
                                <canvas id="performanceChart" height="300"></canvas>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Nu există date pentru filtrele selectate.
                        </div>
                    @endif

                    <h5>Lista evaluărilor {{ $selectedEmployee ? 'pentru angajatul selectat' : '' }}</h5>
                    
                    @if(count($reviews) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Angajat</th>
                                        <th>Evaluator</th>
                                        <th>Perioada</th>
                                        <th>Data evaluării</th>
                                        <th>Rating general</th>
                                        <th>Acțiuni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>{{ $review->employee->name }}</td>
                                            <td>{{ $review->reviewer->name }}</td>
                                            <td>{{ $review->period }}</td>
                                            <td>{{ $review->review_date->format('d.m.Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $review->overall_rating >= 4 ? 'success' : ($review->overall_rating >= 3 ? 'info' : 'warning') }}">
                                                    {{ $review->overall_rating }}/5
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('performance.show', $review) }}" class="btn btn-sm btn-info">Vizualizează</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Nu există evaluări pentru filtrele selectate.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(count($statistics) > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        
        // Prepare data
        const statistics = @json($statistics);
        const labels = Object.values(statistics).map(stat => stat.name);
        const technicalData = Object.values(statistics).map(stat => stat.avg_technical);
        const communicationData = Object.values(statistics).map(stat => stat.avg_communication);
        const teamworkData = Object.values(statistics).map(stat => stat.avg_teamwork);
        const initiativeData = Object.values(statistics).map(stat => stat.avg_initiative);
        const reliabilityData = Object.values(statistics).map(stat => stat.avg_reliability);
        const overallData = Object.values(statistics).map(stat => stat.avg_overall);
        
        const myChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Tehnic', 'Comunicare', 'Echipă', 'Inițiativă', 'Fiabilitate', 'General'],
                datasets: Object.values(statistics).map((stat, index) => {
                    const color = getRandomColor(index);
                    return {
                        label: stat.name,
                        data: [
                            stat.avg_technical,
                            stat.avg_communication,
                            stat.avg_teamwork,
                            stat.avg_initiative,
                            stat.avg_reliability,
                            stat.avg_overall
                        ],
                        backgroundColor: color + '33', // Add transparency
                        borderColor: color,
                        borderWidth: 2
                    };
                })
            },
            options: {
                scales: {
                    r: {
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        
        function getRandomColor(index) {
            const colors = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', 
                '#FF9F40', '#2E5AAC', '#8BC34A', '#FF5722', '#607D8B'
            ];
            return colors[index % colors.length];
        }
    });
</script>
@endpush
@endif
@endsection