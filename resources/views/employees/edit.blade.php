@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editează Angajat</h1>

    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nume</label>
            <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <input type="text" name="role" class="form-control" value="{{ $employee->role }}" required>
        </div>
        <div class="mb-3">
            <label>Salariu</label>
            <input type="number" name="salary" class="form-control" value="{{ $employee->salary }}" required>
        </div>
        <div class="mb-3">
            <label>Experiență (ani)</label>
            <input type="number" name="experience" class="form-control" value="{{ $employee->experience }}" required>
        </div>
        <button type="submit" class="btn btn-warning">Salvează</button>
    </form>
</div>
@endsection
