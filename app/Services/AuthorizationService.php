<?php

namespace App\Services;

use App\Models\User;
use App\Constants\UserRole;

/**
 * AuthorizationService - Centralized authorization logic
 * 
 * Provides methods for checking user permissions and roles
 * This consolidates all role-based authorization in one place
 */
class AuthorizationService
{
    /**
     * Check if user has a specific permission
     */
    public function can(User $user, string $permission): bool
    {
        return UserRole::hasPermission($user->role, $permission);
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Check if user is staff
     */
    public function isStaff(User $user): bool
    {
        return $user->role === UserRole::STAFF;
    }

    /**
     * Check if user can view senior citizens
     */
    public function canViewSeniorCitizens(User $user): bool
    {
        return $this->can($user, 'view_senior_citizens');
    }

    /**
     * Check if user can create senior citizens
     */
    public function canCreateSeniorCitizens(User $user): bool
    {
        return $this->can($user, 'create_senior_citizens');
    }

    /**
     * Check if user can edit senior citizens
     */
    public function canEditSeniorCitizens(User $user): bool
    {
        return $this->can($user, 'edit_senior_citizens');
    }

    /**
     * Check if user can delete senior citizens (soft delete)
     */
    public function canDeleteSeniorCitizens(User $user): bool
    {
        return $this->can($user, 'delete_senior_citizens');
    }

    /**
     * Check if user can restore senior citizens
     */
    public function canRestoreSeniorCitizens(User $user): bool
    {
        return $this->can($user, 'restore_senior_citizens');
    }

    /**
     * Check if user can view reports
     */
    public function canViewReports(User $user): bool
    {
        return $this->can($user, 'view_reports');
    }

    /**
     * Check if user can export reports
     */
    public function canExportReports(User $user): bool
    {
        return $this->can($user, 'export_reports');
    }

    /**
     * Check if user can view analytics
     */
    public function canViewAnalytics(User $user): bool
    {
        return $this->can($user, 'view_analytics');
    }

    /**
     * Check if user can view pension data
     */
    public function canViewPension(User $user): bool
    {
        return $this->can($user, 'view_pension');
    }

    /**
     * Check if user can update pension status
     */
    public function canUpdatePensionStatus(User $user): bool
    {
        return $this->can($user, 'update_pension_status');
    }

    /**
     * Check if user can claim pension
     */
    public function canClaimPension(User $user): bool
    {
        return $this->can($user, 'claim_pension');
    }

    /**
     * Check if user can distribute age milestones (ADMIN ONLY)
     */
    public function canDistributeAgeMilestone(User $user): bool
    {
        return $this->can($user, 'distribute_age_milestone');
    }

    /**
     * Check if user can view change history
     */
    public function canViewHistory(User $user): bool
    {
        return $this->can($user, 'view_history');
    }

    /**
     * Check if user can view audit logs
     */
    public function canViewAuditLogs(User $user): bool
    {
        return $this->can($user, 'view_audit_logs');
    }

    /**
     * Check if user can manage users (view all users)
     */
    public function canViewUsers(User $user): bool
    {
        return $this->can($user, 'view_users');
    }

    /**
     * Check if user can create users
     */
    public function canCreateUsers(User $user): bool
    {
        return $this->can($user, 'create_users');
    }

    /**
     * Check if user can edit users
     */
    public function canEditUsers(User $user): bool
    {
        return $this->can($user, 'edit_users');
    }

    /**
     * Check if user can change user roles
     */
    public function canChangeUserRole(User $user): bool
    {
        return $this->can($user, 'change_user_role');
    }

    /**
     * Check if user can change user status
     */
    public function canChangeUserStatus(User $user): bool
    {
        return $this->can($user, 'change_user_status');
    }

    /**
     * Check if user can import senior citizens
     */
    public function canImportSeniorCitizens(User $user): bool
    {
        return $this->can($user, 'import_senior_citizens');
    }

    /**
     * Check if user can export users (CSV)
     */
    public function canExportUsers(User $user): bool
    {
        return $this->can($user, 'export_users');
    }

    /**
     * Check if user can view admin dashboard
     */
    public function canViewAdminDashboard(User $user): bool
    {
        return $this->can($user, 'view_admin_dashboard');
    }

    /**
     * Check if user can view staff dashboard
     */
    public function canViewStaffDashboard(User $user): bool
    {
        return $this->can($user, 'view_staff_dashboard');
    }

    /**
     * Get all permissions for user's role
     */
    public function getPermissions(User $user): array
    {
        return UserRole::getPermissions($user->role);
    }

    /**
     * Get display name for user's role
     */
    public function getRoleDisplayName(User $user): string
    {
        return UserRole::getDisplayName($user->role);
    }
}
