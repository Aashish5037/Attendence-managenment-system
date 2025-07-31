<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;

Route::view('/', 'welcome')->name('welcome');

// Dashboard route using controller for flexibility
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employees resource routes
    Route::resource('employees', EmployeeController::class);

    // View employee attendance (custom)
    Route::get('employees/{employee}/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');


    // Attendances resource routes
    Route::resource('attendances', AttendanceController::class);

    // Edit and update attendance overrides (optional, but good to keep)
    Route::get('/attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
    Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

    // Payroll index route
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
});

require __DIR__.'/auth.php';
