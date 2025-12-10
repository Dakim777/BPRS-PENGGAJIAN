<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Salary;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $totalAttendances = Attendance::whereMonth('tanggal', now()->month)->count();
        $totalSalaries = Salary::where('periode', now()->format('Y-m'))->count();

        return view('dashboard', compact('totalEmployees', 'totalAttendances', 'totalSalaries'));
    }
}
