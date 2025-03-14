@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <h4>Bine ai venit, {{ $user->name }}!</h4>
                    
                    <!-- Card pentru pontaj -->
                    <div class="card mt-4">
                        <div class="card-header">Pontaj</div>
                        <div class="card-body">
                            @if ($attendance && $attendance->clock_in)
                                <p>Ai fost pontat astăzi la ora: {{ date('H:i', strtotime($attendance->clock_in)) }}</p>
                                
                                @if ($attendance->clock_out)
                                    <p>Ai ieșit la ora: {{ date('H:i', strtotime($attendance->clock_out)) }}</p>
                                @else
                                    <form action="{{ route('attendances.clockOut') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Pontează ieșirea</button>
                                    </form>
                                @endif
                            @else
                                <form action="{{ route('attendances.clockIn') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Pontează intrarea</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Card pentru task-uri -->
                    <div class="card mt-4">
                        <div class="card-header">Task-uri recente</div>
                        <div class="card-body">
                            @if ($tasks->count() > 0)
                                <ul class="list-group">
                                    @foreach ($tasks as $task)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $task->title }}
                                            <span class="badge bg-primary rounded-pill">{{ $task->points }} puncte</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-info mt-3">Vezi toate task-urile</a>
                            @else
                                <p>Nu ai task-uri active.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Card pentru postări recente -->
                    <div class="card mt-4">
                        <div class="card-header">Postări recente</div>
                        <div class="card-body">
                            @if ($recentPosts->count() > 0)
                                <ul class="list-group">
                                    @foreach ($recentPosts as $post)
                                        <li class="list-group-item">
                                            <h5>{{ $post->title }}</h5>
                                            <p class="text-muted">Postat de {{ $post->user->name }} la {{ $post->created_at->format('d.m.Y H:i') }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-info mt-3">Vezi toate postările</a>
                            @else
                                <p>Nu există postări recente.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Card pentru training-uri -->
                    <div class="card mt-4">
                        <div class="card-header">Training-uri disponibile</div>
                        <div class="card-body">
                            @if ($trainings->count() > 0)
                                <ul class="list-group">
                                    @foreach ($trainings as $training)
                                        <li class="list-group-item">
                                            <h5>{{ $training->title }}</h5>
                                            <p>{{ Str::limit($training->description, 100) }}</p>
                                            @if ($training->link)
                                                <a href="{{ $training->link }}" target="_blank" class="btn btn-sm btn-outline-primary">Accesează cursul</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('trainings.index') }}" class="btn btn-sm btn-info mt-3">Vezi toate training-urile</a>
                            @else
                                <p>Nu există training-uri disponibile.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection