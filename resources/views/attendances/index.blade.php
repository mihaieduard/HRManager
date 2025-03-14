@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Istoric pontaj</span>
                    
                    <div>
                        @php
                            $today = \Carbon\Carbon::now()->toDateString();
                            $todayAttendance = \App\Models\Attendance::where('user_id', auth()->id())
                                                                   ->where('date', $today)
                                                                   ->first();
                        @endphp

                        @if ($todayAttendance && $todayAttendance->clock_in)
                            @if ($todayAttendance->clock_out)
                                <span class="badge bg-success">Pontat complet pentru azi</span>
                            @else
                                <form action="{{ route('attendances.clockOut') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">Pontează ieșirea</button>
                                </form>
                            @endif
                        @else
                            <form action="{{ route('attendances.clockIn') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Pontează intrarea</button>
                            </form>
                        @endif
                    </div>
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Ora de intrare</th>
                                    <th>Ora de ieșire</th>
                                    <th>Durata</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $attendance)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d.m.Y') }}</td>
                                        <td>{{ $attendance->clock_in ? date('H:i', strtotime($attendance->clock_in)) : '-' }}</td>
                                        <td>{{ $attendance->clock_out ? date('H:i', strtotime($attendance->clock_out)) : '-' }}</td>
                                        <td>
                                            @if ($attendance->clock_in && $attendance->clock_out)
                                                @php
                                                    $start = \Carbon\Carbon::parse($attendance->clock_in);
                                                    $end = \Carbon\Carbon::parse($attendance->clock_out);
                                                    $duration = $start->diff($end);
                                                @endphp
                                                {{ $duration->format('%H:%I') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $attendance->notes ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nu există înregistrări de pontaj.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection