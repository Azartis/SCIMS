<?php

namespace App\Constants;

/**
 * UserRole - Define system roles and their permissions
 */
class UserRole
{
    // Role definitions
    public const ADMIN = 'admin';
    public const STAFF = 'staff';

    // Valid roles list
    public const VALID_ROLES = [self::ADMIN, self::STAFF];

    // Feature permissions by role
    public const PERMISSIONS = [
        self::ADMIN => [
            // Core Data Management
            'view_senior_citizens' => true,
            'create_senior_citizens' => true,
            'edit_senior_citizens' => true,
            'delete_senior_citizens' => true,
            'restore_senior_citizens' => true,

            // Reports & Analytics
            'view_reports' => true,
            'export_reports' => true,
            'view_analytics' => true,

            // Social Pension (SPISC)
            'view_pension' => true,
            'update_pension_status' => true,
            'claim_pension' => true,
            'distribute_age_milestone' => true,

            // History & Audit
            'view_history' => true,
            'view_audit_logs' => true,

            // User Management (ADMIN ONLY)
            'view_users' => true,
            'create_users' => true,
            'edit_users' => true,
            'delete_users' => true,
            'change_user_role' => true,
            'change_user_status' => true,

            // Imports
            'import_senior_citizens' => true,
            'export_users' => true,

            // Dashboard
            'view_admin_dashboard' => true,
            'view_staff_dashboard' => true,
        ],
        self::STAFF => [
            // Core Data Management (Read/Write but NO delete)
            'view_senior_citizens' => true,
            'create_senior_citizens' => true,
            'edit_senior_citizens' => true,
            'delete_senior_citizens' => false,  // Staff cannot permanently delete
            'restore_senior_citizens' => false, // Staff cannot restore

            // Reports & Analytics
            'view_reports' => true,
            'export_reports' => true,
            'view_analytics' => true,

            // Social Pension (SPISC)
            'view_pension' => true,
            'update_pension_status' => true,
            'claim_pension' => true,
            'distribute_age_milestone' => false,  // Only admins can distribute age milestones

            // History & Audit
            'view_history' => true,
            'view_audit_logs' => false,  // Staff cannot view system audit logs

            // User Management (STAFF CANNOT)
            'view_users' => false,
            'create_users' => false,
            'edit_users' => false,
            'delete_users' => false,
            'change_user_role' => false,
            'change_user_status' => false,

            // Imports
            'import_senior_citizens' => false,  // Only admins can import
            'export_users' => false,

            // Dashboard
            'view_admin_dashboard' => false,
            'view_staff_dashboard' => true,
        ],
    ];

    /**
     * Get all permissions for a role
     */
    public static function getPermissions(string $role): array
    {
        return self::PERMISSIONS[$role] ?? [];
    }

    /**
     * Check if a role has a specific permission
     */
    public static function hasPermission(string $role, string $permission): bool
    {
        $permissions = self::getPermissions($role);
        return $permissions[$permission] ?? false;
    }

    /**
     * Get display name for role
     */
    public static function getDisplayName(string $role): string
    {
        return match ($role) {
            self::ADMIN => 'Administrator',
            self::STAFF => 'Staff Member',
            default => 'Unknown',
        };
    }

    /**
     * Get role badge color
     */
    public static function getBadgeColor(string $role): string
    {
        return match ($role) {
            self::ADMIN => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
            self::STAFF => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
            default => 'bg-slate-100 dark:bg-slate-900/30 text-slate-700 dark:text-slate-400',
        };
    }

    /**
     * Validate role
     */
    public static function isValid(string $role): bool
    {
        return in_array($role, self::VALID_ROLES);
    }
}
