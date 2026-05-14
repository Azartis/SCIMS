<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PensionDistribution;
use App\Services\AuthorizationService;

class PensionDistributionPolicy
{
    public function __construct(private AuthorizationService $authService)
    {
    }

    /**
     * Determine if the user can view pension distributions
     */
    public function viewAny(User $user): bool
    {
        return $this->authService->canViewPension($user);
    }

    /**
     * Determine if the user can view a pension distribution
     */
    public function view(User $user, PensionDistribution $pension): bool
    {
        return $this->authService->canViewPension($user);
    }

    /**
     * Determine if the user can update pension status
     */
    public function updateStatus(User $user): bool
    {
        return $this->authService->canUpdatePensionStatus($user);
    }

    /**
     * Determine if the user can claim pension
     */
    public function claim(User $user): bool
    {
        return $this->authService->canClaimPension($user);
    }

    /**
     * Determine if the user can create a pension distribution
     */
    public function create(User $user): bool
    {
        return $this->authService->canUpdatePensionStatus($user);
    }

    /**
     * Determine if the user can distribute age milestones (ADMIN ONLY)
     */
    public function distributeAgeMilestone(User $user): bool
    {
        return $this->authService->canDistributeAgeMilestone($user);
    }

    /**
     * Determine if the user can export pension distributions
     */
    public function export(User $user): bool
    {
        return $this->authService->canViewPension($user);
    }
}
