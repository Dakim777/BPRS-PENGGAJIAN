<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|unique:employees,nip',
            'nama' => 'required|string',
            'email' => 'nullable|email',
            'gaji_pokok' => 'numeric',
            'tunjangan' => 'nullable|numeric',
            'potongan' => 'nullable|numeric',
        ]);

        Employee::create($data);
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'nip' => 'required|unique:employees,nip,' . $employee->id,
            'nama' => 'required|string',
            'email' => 'nullable|email',
            // Pastikan field keuangan ini ikut divalidasi dan diupdate
            'gaji_pokok' => 'nullable|numeric',
            'tunjangan' => 'nullable|numeric',
            'potongan' => 'nullable|numeric',
        ]);

        $employee->update($data);
        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Karyawan dihapus');
    }
}