@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Adaugă Angajat</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nume</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <input type="text" name="role" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Salariu</label>
            <input type="number" name="salary" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Experiență (ani)</label>
            <input type="number" name="experience" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Adaugă</button>
    </form>
</div>
@endsection
