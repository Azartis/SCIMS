<?php

namespace App\Policies;

use App\Models\User;
use App\Services\AuthorizationService;

class ReportPolicy
{
    public function __construct(private AuthorizationService $authService)
    {
    }

    /**
     * Determine if the user can view reports
     */
    public function viewAny(User $user): bool
    {
        return $this->authService->canViewReports($user);
    }

    /**
     * Determine if the user can export reports
     */
    public function export(User $user): bool
    {
        return $this->authService->canExportReports($user);
    }

    /**
     * Determine if the user can view analytics
     */
    public function viewAnalytics(User $user): bool
    {
        return $this->authService->canViewAnalytics($user);
    }
}
