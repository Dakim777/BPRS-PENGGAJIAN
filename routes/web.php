<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\Auth\LoginController;

// 1. Route Login & Logout (Public / Guest)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// 2. Protected Routes (Hanya Admin Login)
Route::middleware(['auth'])->group(function () {
    
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
    
    // --> ROUTE BARU PDF <--
    Route::get('salaries/{salary}/pdf', [SalaryController::class, 'downloadPdf'])->name('salaries.pdf');
    
    Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');
});