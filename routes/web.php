<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeniorCitizenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Global change history
Route::get('/history', [\App\Http\Controllers\SeniorCitizenController::class, 'history'])
    ->middleware(['auth', 'verified'])
    ->name('history');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Senior Citizens Routes
    Route::resource('senior-citizens', SeniorCitizenController::class);
    Route::get('senior-citizens-archive', [SeniorCitizenController::class, 'archive'])->name('senior-citizens.archive');
    Route::post('senior-citizens/{id}/restore', [SeniorCitizenController::class, 'restore'])->name('senior-citizens.restore');
    Route::get('senior-citizens/{id}/audit-history', [SeniorCitizenController::class, 'auditHistory'])->name('senior-citizens.audit-history');

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/statistics', [ReportController::class, 'statistics'])->name('reports.statistics');
    // Report detail routes
    Route::get('/reports/health', [ReportController::class, 'health'])->name('reports.health');
    Route::get('/reports/health/export', [ReportController::class, 'exportHealth'])->name('reports.health.export');
    Route::get('/reports/barangay', [ReportController::class, 'barangay'])->name('reports.barangay');
    Route::get('/reports/barangay/export', [ReportController::class, 'exportBarangay'])->name('reports.barangay.export');
    Route::get('/reports/deceased', [ReportController::class, 'deceased'])->name('reports.deceased');
    Route::get('/reports/deceased/{id}', [ReportController::class, 'deceasedShow'])->name('reports.deceased.show');

    // SPISC placeholder route (top-level card on dashboard)
    Route::get('/spisc', function () {
        return view('spisc');
    })->name('spisc.index');

    // Audit Logs Routes (Admin only)
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show'])->middleware('admin');

    // User Management Routes (Admin only)
    Route::resource('users', UserController::class)->middleware('admin');
});

require __DIR__.'/auth.php';
