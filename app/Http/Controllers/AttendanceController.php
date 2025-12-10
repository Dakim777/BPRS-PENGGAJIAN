<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee');

        if ($request->filled('nip')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('nip', $request->nip)->orWhere('nama', 'like', '%'.$request->nip.'%');
            });
        }

        $attendances = $query->orderBy('tanggal', 'desc')->paginate(20);
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,cuti,sakit,absen,lembur',
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable',
        ]);

        // Mencegah duplikasi: Update jika sudah ada tanggal yang sama untuk user ini
        Attendance::updateOrCreate(
            [
                'employee_id' => $data['employee_id'],
                'tanggal' => $data['tanggal']
            ],
            $data
        );

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil disimpan/diperbarui.');
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::all();
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,cuti,sakit,absen,lembur',
            'jam_masuk' => 'nullable',
            'jam_keluar' => 'nullable',
        ]);

        $attendance->update($data);
        return redirect()->route('attendances.index')->with('success', 'Absensi diperbarui');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Absensi dihapus');
    }
}