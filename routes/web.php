<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeniorCitizenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'check.status'])
    ->name('dashboard');

// Global change history
Route::get('/history', [\App\Http\Controllers\SeniorCitizenController::class, 'history'])
    ->middleware(['auth', 'verified', 'check.status'])
    ->name('history');

Route::middleware(['auth','check.status'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // log out other browser sessions
    Route::post('/profile/other-sessions', [ProfileController::class, 'destroyOtherSessions'])
        ->name('profile.other-sessions.destroy');

    // Senior Citizens CRUD (main routes)
    Route::resource('senior-citizens', SeniorCitizenController::class);

    // archive view for soft-deleted records
    Route::get('senior-citizens/archive', [SeniorCitizenController::class, 'archive'])->name('senior-citizens.archive');

    // additional helper endpoints
    Route::post('senior-citizens/{id}/restore', [SeniorCitizenController::class, 'restore'])->name('senior-citizens.restore');
    Route::get('senior-citizens/{id}/audit-history', [SeniorCitizenController::class, 'auditHistory'])->name('senior-citizens.audit-history');
    Route::post('senior-citizens/{seniorCitizen}/mark-deceased', [SeniorCitizenController::class, 'markDeceased'])->name('senior-citizens.mark-deceased');

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

    // SPISC listing of social pension recipients
    Route::get('/spisc', [\App\Http\Controllers\SpiscController::class, 'index'])->name('spisc.index');
    Route::post('/spisc/{seniorCitizen}/update-status', [\App\Http\Controllers\SpiscController::class, 'updateStatus'])->name('spisc.update-status');

    // Pension Distributions (handled via SPISC modal now)
    Route::post('pension-distributions', [\App\Http\Controllers\PensionDistributionController::class, 'store'])
        ->name('pension-distributions.store');
    Route::post('pension-distributions/{pension_distribution}/claim', [\App\Http\Controllers\PensionDistributionController::class, 'claim'])
        ->name('pension-distributions.claim');
    Route::get('pension-distributions/export', [\App\Http\Controllers\PensionDistributionController::class, 'export'])
        ->name('pension-distributions.export');

    // Age Milestone Distributions (80, 85, 90, 95, 100 years old)
    Route::get('/age-milestones', [\App\Http\Controllers\AgeMilestoneController::class, 'index'])->name('age-milestones.index');
    Route::post('/age-milestones/{age}/distribute', [\App\Http\Controllers\AgeMilestoneController::class, 'distribute'])->name('age-milestones.distribute');
    Route::post('/age-milestones/distribution/{distribution}/claim', [\App\Http\Controllers\AgeMilestoneController::class, 'claimDistribution'])->name('age-milestones.distribution.claim');

    // Audit Logs Routes (Admin only)
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show'])->middleware('admin');

    // Admin Routes (Admin only)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'userManagement'])->name('users');
    });

    // User Management Routes (Admin only) - includes CSV export
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::get('/users/export/csv', [UserController::class, 'export'])->name('users.export');
    });
});

require __DIR__.'/auth.php';
