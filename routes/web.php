<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeniorCitizenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Senior Citizens Routes
    Route::resource('senior-citizens', SeniorCitizenController::class);
    Route::get('senior-citizens-archive', [SeniorCitizenController::class, 'archive'])->name('senior-citizens.archive');
    Route::post('senior-citizens/{id}/restore', [SeniorCitizenController::class, 'restore'])->name('senior-citizens.restore');

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/statistics', [ReportController::class, 'statistics'])->name('reports.statistics');

    // User Management Routes (Admin only)
    Route::resource('users', UserController::class)->middleware('admin');
});

require __DIR__.'/auth.php';
