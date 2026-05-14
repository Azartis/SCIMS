<?php

namespace App\Providers;

use App\Models\User;
use App\Models\SeniorCitizen;
use App\Models\PensionDistribution;
use App\Models\AuditLog;
use App\Policies\UserPolicy;
use App\Policies\SeniorCitizenPolicy;
use App\Policies\ReportPolicy;
use App\Policies\PensionDistributionPolicy;
use App\Policies\AuditLogPolicy;
use App\Services\BaseService;
use App\Services\CacheService;
use App\Services\DashboardService;
use App\Services\SeniorCitizenService;
use App\Services\ReportService;
use App\Services\AuthorizationService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        $this->app->singleton(AuthorizationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        
        // Model casts configuration
        
        // Date formatting
        date_default_timezone_set('UTC');

        // Force HTTPS in production so generated URLs and redirects use https://
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Define Gates using AuthorizationService
        Gate::define('isAdmin', function ($user) {
            return app(AuthorizationService::class)->isAdmin($user);
        });

        Gate::define('isStaff', function ($user) {
            return app(AuthorizationService::class)->isStaff($user);
        });

        // Register policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(SeniorCitizen::class, SeniorCitizenPolicy::class);
        Gate::policy(PensionDistribution::class, PensionDistributionPolicy::class);
        Gate::policy(AuditLog::class, AuditLogPolicy::class);

        
        
    }
}
