<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SeniorCitizen;
use App\Services\AuthorizationService;

class SeniorCitizenPolicy
{
    public function __construct(private AuthorizationService $authService)
    {
    }

    /**
     * Determine if the user can view any senior citizen
     */
    public function viewAny(User $user): bool
    {
        return $this->authService->canViewSeniorCitizens($user);
    }

    /**
     * Determine if the user can view a senior citizen
     */
    public function view(User $user, SeniorCitizen $seniorCitizen): bool
    {
        return $this->authService->canViewSeniorCitizens($user);
    }

    /**
     * Determine if the user can create a senior citizen
     */
    public function create(User $user): bool
    {
        return $this->authService->canCreateSeniorCitizens($user);
    }

    /**
     * Determine if the user can update a senior citizen
     */
    public function update(User $user, SeniorCitizen $seniorCitizen): bool
    {
        return $this->authService->canEditSeniorCitizens($user);
    }

    /**
     * Determine if the user can delete (soft delete) a senior citizen
     */
    public function delete(User $user, SeniorCitizen $seniorCitizen): bool
    {
        return $this->authService->canDeleteSeniorCitizens($user);
    }

    /**
     * Determine if the user can restore a soft-deleted senior citizen
     */
    public function restore(User $user, SeniorCitizen $seniorCitizen): bool
    {
        return $this->authService->canRestoreSeniorCitizens($user);
    }

    /**
     * Determine if the user can permanently delete a senior citizen
     */
    public function forceDelete(User $user, SeniorCitizen $seniorCitizen): bool
    {
        // Only admins can permanently delete
        return $this->authService->isAdmin($user);
    }

    /**
     * Determine if the user can mark a senior citizen as deceased
     */
    public function markDeceased(User $user, SeniorCitizen $seniorCitizen): bool
    {
        return $this->authService->canEditSeniorCitizens($user);
    }

    /**
     * Determine if the user can view audit history for a senior citizen
     */
    public function viewAuditHistory(User $user, SeniorCitizen $seniorCitizen): bool
    {
        // Both staff and admin can view audit history for individual records
        // (Different from system-wide audit logs which only admins can view)
        return true;
    }
}
