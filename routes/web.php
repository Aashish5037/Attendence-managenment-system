<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;

// Public route
Route::view('/', 'welcome')->name('welcome');

// Dashboard route (main dashboard)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employees resource routes
    Route::resource('employees', EmployeeController::class);

    // Employee attendance custom route
    Route::get('employees/{employee}/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');

    // Attendance resource routes
    Route::resource('attendances', AttendanceController::class);

    // Attendance edit and update overrides
    Route::get('/attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
    Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

    // Payroll routes
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');

    // Optional: Attendance dashboard with a different route if needed
    // Route::get('/attendance-dashboard', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
});

require __DIR__.'/auth.php';
