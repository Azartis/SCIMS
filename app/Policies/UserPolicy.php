<?php

namespace App\Policies;

use App\Models\User;
use App\Services\AuthorizationService;

class UserPolicy
{
    public function __construct(private AuthorizationService $authService)
    {
    }

    /**
     * Only admins can manage users (view user list)
     */
    public function isAdmin(User $user): bool
    {
        return $this->authService->isAdmin($user);
    }

    /**
     * Check if user can view users
     */
    public function viewUsers(User $user): bool
    {
        return $this->authService->canViewUsers($user);
    }

    /**
     * Check if user can create users
     */
    public function createUsers(User $user): bool
    {
        return $this->authService->canCreateUsers($user);
    }

    /**
     * Check if user can edit a specific user
     */
    public function editUser(User $user, User $targetUser): bool
    {
        return $this->authService->canEditUsers($user);
    }

    /**
     * Check if user can delete a specific user
     */
    public function deleteUser(User $user, User $targetUser): bool
    {
        return $this->authService->isAdmin($user) && $user->id !== $targetUser->id;
    }

    /**
     * Check if user can update user status
     */
    public function updateUserStatus(User $user, User $targetUser): bool
    {
        return $this->authService->canChangeUserStatus($user);
    }

    /**
     * Check if user can change user role
     */
    public function changeUserRole(User $user, User $targetUser): bool
    {
        return $this->authService->canChangeUserRole($user);
    }
}
