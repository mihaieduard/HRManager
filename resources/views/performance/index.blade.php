@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Evaluări performanță</span>
                    @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'HR')
                    <a href="{{ route('performance.create') }}" class="btn btn-primary btn-sm">Creează evaluare</a>
                    @endif
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
                                        <th>Status</th>
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
                                                @if($review->overall_rating > 0)
                                                    <span class="badge bg-{{ $review->overall_rating >= 4 ? 'success' : ($review->overall_rating >= 3 ? 'info' : 'warning') }}">
                                                        {{ $review->overall_rating }}/5
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($review->status === 'draft')
                                                    <span class="badge bg-secondary">Draft</span>
                                                @elseif($review->status === 'submitted')
                                                    <span class="badge bg-primary">Trimisă</span>
                                                @elseif($review->status === 'acknowledged')
                                                    <span class="badge bg-success">Confirmată</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('performance.show', $review) }}" class="btn btn-sm btn-info">Vizualizează</a>
                                                
                                                @if(auth()->id() === $review->reviewer_id && $review->status === 'draft')
                                                    <a href="{{ route('performance.edit', $review) }}" class="btn btn-sm btn-warning">Editează</a>
                                                    
                                                    <form action="{{ route('performance.destroy', $review) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Ești sigur că vrei să ștergi această evaluare?')">Șterge</button>
                                                    </form>
                                                @endif
                                                
                                                @if(auth()->id() === $review->reviewer_id && $review->status === 'draft')
                                                    <form action="{{ route('performance.submit', $review) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Ești sigur că vrei să trimiți această evaluare?')">Trimite</button>
                                                    </form>
                                                @endif
                                                
                                                @if(auth()->id() === $review->user_id && $review->status === 'submitted')
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#acknowledgeModal-{{ $review->id }}">
                                                        Confirmă
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            Nu există evaluări de performanță disponibile.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($reviews as $review)
    @if(auth()->id() === $review->user_id && $review->status === 'submitted')
    <!-- Modal pentru confirmare -->
    <div class="modal fade" id="acknowledgeModal-{{ $review->id }}" tabindex="-1" aria-labelledby="acknowledgeModalLabel-{{ $review->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="acknowledgeModalLabel-{{ $review->id }}">Confirmă evaluarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('performance.acknowledge', $review) }}" method="POST">
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
@endforeach
@endsection