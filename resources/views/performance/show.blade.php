@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Evaluare performanță - {{ $performance->employee->name }}</span>
                    <a href="{{ route('performance.index') }}" class="btn btn-primary btn-sm">Înapoi la listă</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informații generale</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Angajat:</dt>
                                <dd class="col-sm-8">{{ $performance->employee->name }}</dd>
                                
                                <dt class="col-sm-4">Evaluator:</dt>
                                <dd class="col-sm-8">{{ $performance->reviewer->name }}</dd>
                                
                                <dt class="col-sm-4">Data evaluării:</dt>
                                <dd class="col-sm-8">{{ $performance->review_date->format('d.m.Y') }}</dd>
                                
                                <dt class="col-sm-4">Perioada evaluată:</dt>
                                <dd class="col-sm-8">{{ $performance->period }}</dd>
                                
                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    @if($performance->status === 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($performance->status === 'submitted')
                                        <span class="badge bg-primary">Trimisă</span>
                                    @elseif($performance->status === 'acknowledged')
                                        <span class="badge bg-success">Confirmată ({{ $performance->employee_acknowledged_date->format('d.m.Y') }})</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <h5>Evaluare generală</h5>
                            <div class="text-center mb-3">
                                <h1 class="display-4">{{ $performance->overall_rating }}/5</h1>
                                <p>Rating general</p>
                            </div>
                            <div class="progress mb-3" style="height: 30px;">
                                <div class="progress-bar {{ $performance->overall_rating >= 4 ? 'bg-success' : ($performance->overall_rating >= 3 ? 'bg-info' : 'bg-warning') }}" 
                                     role="progressbar" style="width: {{ $performance->overall_rating * 20 }}%" 
                                     aria-valuenow="{{ $performance->overall_rating }}" aria-valuemin="0" aria-valuemax="5">
                                    {{ $performance->overall_rating }}/5
                                </div>
                            </div>
                            @if($performance->overall_comments)
                                <p><strong>Comentarii generale:</strong> {{ $performance->overall_comments }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Realizări și Obiective</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-header">Realizări</div>
                                        <div class="card-body">
                                            {{ $performance->accomplishments ?: 'Nicio realizare menționată.' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-header">Domenii pentru îmbunătățire</div>
                                        <div class="card-body">
                                            {{ $performance->areas_for_improvement ?: 'Niciun domeniu pentru îmbunătățire menționat.' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-header">Obiective pentru următoarea perioadă</div>
                                        <div class="card-body">
                                            {{ $performance->goals ?: 'Niciun obiectiv menționat.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Evaluarea competențelor</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Competență</th>
                                        <th>Rating</th>
                                        <th>Comentarii</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Competențe tehnice</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar {{ $performance->technical_skills_rating >= 4 ? 'bg-success' : ($performance->technical_skills_rating >= 3 ? 'bg-info' : 'bg-warning') }}" 
                                                     role="progressbar" style="width: {{ $performance->technical_skills_rating * 20 }}%" 
                                                     aria-valuenow="{{ $performance->technical_skills_rating }}" aria-valuemin="0" aria-valuemax="5">
                                                    {{ $performance->technical_skills_rating }}/5
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $performance->technical_skills_comments ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Comunicare</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar {{ $performance->communication_rating >= 4 ? 'bg-success' : ($performance->communication_rating >= 3 ? 'bg-info' : 'bg-warning') }}" 
                                                     role="progressbar" style="width: {{ $performance->communication_rating * 20 }}%" 
                                                     aria-valuenow="{{ $performance->communication_rating }}" aria-valuemin="0" aria-valuemax="5">
                                                    {{ $performance->communication_rating }}/5
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $performance->communication_comments ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Lucru în echipă</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar {{ $performance->teamwork_rating >= 4 ? 'bg-success' : ($performance->teamwork_rating >= 3 ? 'bg-info' : 'bg-warning') }}" 
                                                     role="progressbar" style="width: {{ $performance->teamwork_rating * 20 }}%" 
                                                     aria-valuenow="{{ $performance->teamwork_rating }}" aria-valuemin="0" aria-valuemax="5">
                                                    {{ $performance->teamwork_rating }}/5
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $performance->teamwork_comments ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Inițiativă</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar {{ $performance->initiative_rating >= 4 ? 'bg-success' : ($performance->initiative_rating >= 3 ? 'bg-info' : 'bg-warning') }}" 
                                                     role="progressbar" style="width: {{ $performance->initiative_rating * 20 }}%" 
                                                     aria-valuenow="{{ $performance->initiative_rating }}" aria-valuemin="0" aria-valuemax="5">
                                                    {{ $performance->initiative_rating }}/5
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $performance->initiative_comments ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fiabilitate</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar {{ $performance->reliability_rating >= 4 ? 'bg-success' : ($performance->reliability_rating >= 3 ? 'bg-info' : 'bg-warning') }}" 
                                                     role="progressbar" style="width: {{ $performance->reliability_rating * 20 }}%" 
                                                     aria-valuenow="{{ $performance->reliability_rating }}" aria-valuemin="0" aria-valuemax="5">
                                                    {{ $performance->reliability_rating }}/5
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $performance->reliability_comments ?: '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($performance->employee_acknowledged && $performance->employee_comments)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Comentarii angajat</div>
                                <div class="card-body">
                                    {{ $performance->employee_comments }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex">
                                <a href="{{ route('performance.index') }}" class="btn btn-secondary me-2">Înapoi la listă</a>
                                
                                @if(request()->user()->id === $performance->reviewer_id && $performance->status === 'draft')
                                    <a href="{{ route('performance.edit', $performance) }}" class="btn btn-warning me-2">Editează</a>
                                    
                                    <form action="{{ route('performance.submit', $performance) }}" method="POST" class="me-2">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Ești sigur că vrei să trimiți această evaluare?')">Trimite</button>
                                    </form>
                                    
                                    <form action="{{ route('performance.destroy', $performance) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Ești sigur că vrei să ștergi această evaluare?')">Șterge</button>
                                    </form>
                                @endif
                                
                                @if(request()->user()->id === $performance->user_id && $performance->status === 'submitted')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acknowledgeModal">
                                        Confirmă evaluarea
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(request()->user()->id === $performance->user_id && $performance->status === 'submitted')
<!-- Modal pentru confirmare -->
<div class="modal fade" id="acknowledgeModal" tabindex="-1" aria-labelledby="acknowledgeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acknowledgeModalLabel">Confirmă evaluarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('performance.acknowledge', $performance) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="employee_comments" class="form-label">Comentarii (opțional)</label>
                        <textarea class="form-control" id="employee_comments" name="employee_comments" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                    <button type="submit" class="btn btn-success">Confirmă evaluarea</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection