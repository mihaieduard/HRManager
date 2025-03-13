@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista Angajați</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Adaugă Angajat</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nume</th>
                <th>Rol</th>
                <th>Salariu</th>
                <th>Experiență</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->role }}</td>
                    <td>{{ $employee->salary }} RON</td>
                    <td>{{ $employee->experience }} ani</td>
                    <td>
                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info">Detalii</a>
                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">Editează</a>
                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Sigur vrei să ștergi?')">Șterge</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
