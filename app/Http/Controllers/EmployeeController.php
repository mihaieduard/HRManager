<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // ✅ Listare angajați
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    // ✅ Afișare formular de creare
    public function create()
    {
        return view('employees.create');
    }

    // ✅ Salvare angajat nou
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'contract' => 'nullable|string|max:255',
            'experience' => 'required|integer|min:0',
        ]);

        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Angajat adăugat cu succes!');
    }

    // ✅ Afișare detalii angajat
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    // ✅ Afișare formular de editare
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // ✅ Salvare modificări
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'contract' => 'nullable|string|max:255',
            'experience' => 'required|integer|min:0',
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Angajat modificat cu succes!');
    }

    // ✅ Ștergere angajat
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Angajat șters cu succes!');
    }
}
