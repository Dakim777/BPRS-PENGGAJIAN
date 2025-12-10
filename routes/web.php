<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\Auth\LoginController; // Tambahkan ini

// Route Login & Logout (Bisa diakses Guest)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protect Routes (Hanya User Login)
Route::middleware(['auth'])->group(function () {
    
    // Redirect root ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('employees', EmployeeController::class);
    Route::resource('attendances', AttendanceController::class)->except(['show']);

    // Salaries Routes
    Route::get('salaries/report', [SalaryController::class, 'report'])->name('salaries.report');
    Route::post('salaries/calculate', [SalaryController::class, 'calculate'])->name('salaries.calculate');
    Route::post('salaries/store', [SalaryController::class, 'store'])->name('salaries.store');
    Route::patch('salaries/{salary}/status', [SalaryController::class, 'updateStatus'])->name('salaries.updateStatus');
    Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');
});