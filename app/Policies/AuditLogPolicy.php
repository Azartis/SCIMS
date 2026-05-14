<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuditLog;
use App\Services\AuthorizationService;

class AuditLogPolicy
{
    public function __construct(private AuthorizationService $authService)
    {
    }

    /**
     * Determine if the user can view audit logs (ADMIN ONLY)
     */
    public function viewAny(User $user): bool
    {
        return $this->authService->canViewAuditLogs($user);
    }

    /**
     * Determine if the user can view a specific audit log (ADMIN ONLY)
     */
    public function view(User $user, AuditLog $auditLog): bool
    {
        return $this->authService->canViewAuditLogs($user);
    }
}
