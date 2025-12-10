<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HolidayController; // <--- Pastikan Controller ini di-import

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. RUTE PUBLIK (Login & Logout)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// 2. RUTE PROTECTED (Hanya Admin yang sudah Login)
Route::middleware(['auth'])->group(function () {
    
    // Redirect root ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data Karyawan
    Route::resource('employees', EmployeeController::class);

    // Absensi
    Route::resource('attendances', AttendanceController::class)->except(['show']);

    // --- MANAJEMEN HARI LIBUR (BARU) ---
    Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'destroy']);

    // Penggajian (Termasuk Cetak PDF)
    Route::get('salaries/report', [SalaryController::class, 'report'])->name('salaries.report');
    Route::post('salaries/calculate', [SalaryController::class, 'calculate'])->name('salaries.calculate');
    Route::post('salaries/store', [SalaryController::class, 'store'])->name('salaries.store');
    Route::patch('salaries/{salary}/status', [SalaryController::class, 'updateStatus'])->name('salaries.updateStatus');
    Route::get('salaries/{salary}/pdf', [SalaryController::class, 'downloadPdf'])->name('salaries.pdf'); // Route PDF
    Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');

});