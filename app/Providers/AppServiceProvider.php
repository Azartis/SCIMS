<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Services\BaseService;
use App\Services\CacheService;
use App\Services\DashboardService;
use App\Services\SeniorCitizenService;
use App\Services\ReportService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * Services are registered as singletons for consistent caching
     * and performance. Dependency injection automatically resolves
     * service dependencies through constructor injection.
     */
    public function register(): void
    {
        // Register core services as singletons
        $this->app->singleton(CacheService::class);
        $this->app->singleton(DashboardService::class);
        $this->app->singleton(SeniorCitizenService::class);
        $this->app->singleton(ReportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Model casts configuration
        
        // Date formatting
        date_default_timezone_set('UTC');

        // Define Gates
        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        // Register policies
        Gate::policy(User::class, UserPolicy::class);
    }
}
