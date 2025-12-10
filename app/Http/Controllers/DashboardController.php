<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Salary;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Set Waktu ke Asia/Jakarta
        $now = Carbon::now('Asia/Jakarta');
        
        $currentMonth = $now->month;
        $periode = $now->format('Y-m');
        $today = $now->format('Y-m-d'); // Contoh: 2025-12-10

        // 1. Statistik Utama
        $totalEmployees = Employee::count();
        $totalExpenditure = Salary::where('periode', $periode)->sum('gaji_bersih');
        
        $pendingSalaries = Salary::where('periode', $periode)
            ->where('status_pembayaran', 'pending')
            ->count();

        // --- PERBAIKAN DI SINI ---
        // Menghitung yang Hadir ATAU Lembur pada tanggal hari ini
        $attendanceToday = Attendance::whereDate('tanggal', $today)
            ->whereIn('status', ['hadir', 'lembur']) 
            ->count();
        
        // 2. Data Alpha (Untuk Widget Merah)
        // Status 'absen' di database artinya ALPHA (Tidak Masuk)
        $absentEmployees = Attendance::with('employee')
            ->whereMonth('tanggal', $currentMonth)
            ->where('status', 'absen') 
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // 3. Aktivitas Terakhir
        $recentActivities = Attendance::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEmployees', 
            'totalExpenditure', 
            'pendingSalaries',
            'attendanceToday',
            'absentEmployees',
            'recentActivities',
            'periode'
        ));
    }
}