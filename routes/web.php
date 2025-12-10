<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('employees', EmployeeController::class);
Route::resource('attendances', AttendanceController::class)->except(['show']);

// Salaries Routes
Route::get('salaries/report', [SalaryController::class, 'report'])->name('salaries.report');
Route::post('salaries/calculate', [SalaryController::class, 'calculate'])->name('salaries.calculate'); // Hitung (Preview)
Route::post('salaries/store', [SalaryController::class, 'store'])->name('salaries.store'); // Simpan ke DB
Route::patch('salaries/{salary}/status', [SalaryController::class, 'updateStatus'])->name('salaries.updateStatus'); // Update Status
Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');