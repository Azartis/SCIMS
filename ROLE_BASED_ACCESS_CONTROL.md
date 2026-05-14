# SCIMS Role-Based Access Control (RBAC) Matrix

## Overview
This document defines the complete role-based access control matrix for the SCIMS (Senior Citizen Information Management System).

## Roles

### 1. **Admin** (Administrator)
- **Purpose**: System administration, user management, and audit oversight
- **Primary Responsibilities**:
  - Manage system users (create, edit, delete, change roles/status)
  - Monitor system activity through audit logs
  - Import senior citizen data in bulk
  - Full access to all core features

### 2. **Staff** (Staff Member)
- **Purpose**: Day-to-day operations and data management
- **Primary Responsibilities**:
  - Manage senior citizen records (with restrictions on deletion)
  - Generate reports and analytics
  - Process social pension (SPISC) activities
  - View change history for records
  - **Cannot**: Manage users, view system audit logs, import data, delete records permanently, distribute age milestone bonuses

---

## Permissions Matrix

| Feature | Admin | Staff | Notes |
|---------|-------|-------|-------|
| **SENIOR CITIZENS MANAGEMENT** | | | |
| View / List | ✅ | ✅ | Both can browse all records |
| Create | ✅ | ✅ | Both can add new records |
| Edit | ✅ | ✅ | Both can update records |
| Delete (Soft) | ✅ | ❌ | Only admins can soft-delete |
| Restore | ✅ | ❌ | Only admins can restore deleted records |
| Mark as Deceased | ✅ | ✅ | Both can update status |
| View Individual Audit History | ✅ | ✅ | Record-level history available to both |
| **REPORTS & ANALYTICS** | | | |
| View Reports | ✅ | ✅ | Both can access report builder |
| Export Reports | ✅ | ✅ | Both can download report data |
| View Analytics | ✅ | ✅ | Both can access analytics dashboard |
| **SOCIAL PENSION (SPISC)** | | | |
| View Pension Data | ✅ | ✅ | Both can browse pension records |
| Update Pension Status | ✅ | ✅ | Both can update status |
| Claim Pension | ✅ | ✅ | Both can process claims |
| Distribute Age Milestones | ✅ | ❌ | Only admins (80, 85, 90, 95, 100 years) |
| **CHANGE HISTORY** | | | |
| View Change History | ✅ | ✅ | Both can access system change log |
| **USER MANAGEMENT** | | | |
| View Users | ✅ | ❌ | Admins only |
| Create Users | ✅ | ❌ | Admins only |
| Edit Users | ✅ | ❌ | Admins only |
| Delete Users | ✅ | ❌ | Admins only |
| Change User Role | ✅ | ❌ | Admins only |
| Change User Status | ✅ | ❌ | Admins only |
| Export Users (CSV) | ✅ | ❌ | Admins only |
| **AUDIT & MONITORING** | | | |
| View System Audit Logs | ✅ | ❌ | Admins only - system-wide activity |
| **DATA IMPORT/EXPORT** | | | |
| Import Senior Citizens (CSV/Excel) | ✅ | ❌ | Admins only |
| **DASHBOARD** | | | |
| View Admin Dashboard | ✅ | ❌ | System metrics, user management shortcuts |
| View Staff Dashboard | ✅ | ✅ | Core operational dashboard |

---

## Implementation Details

### Authorization Service
The `AuthorizationService` class provides centralized permission checking:

```php
$auth = app(AuthorizationService::class);

// Check if user can perform action
if ($auth->canDeleteSeniorCitizens($user)) {
    // Allow deletion
}

// Check admin status
if ($auth->isAdmin($user)) {
    // Admin-only code
}
```

### Policies
Each major model has an associated policy for fine-grained control:

- **SeniorCitizenPolicy**: Controls access to senior citizen records
- **UserPolicy**: Controls user management operations
- **ReportPolicy**: Controls report access
- **PensionDistributionPolicy**: Controls pension operations
- **AuditLogPolicy**: Controls audit log access

### Using Policies in Blade Templates
```blade
@can('delete', $seniorCitizen)
    <!-- Show delete button -->
@endcan

@can('isAdmin', auth()->user())
    <!-- Show admin controls -->
@endcan
```

### Using Policies in Controllers
```php
$this->authorize('delete', $seniorCitizen);
// or
$this->authorize('canDeleteSeniorCitizens');
```

---

## Key Restrictions in Place

### **Staff Users Cannot**:
1. **Delete Records**: Staff cannot soft-delete senior citizen records (security audit trail)
2. **Restore Records**: Staff cannot restore deleted records (prevents data recovery without admin oversight)
3. **Manage Users**: Staff has no access to user management features
4. **View Audit Logs**: Staff cannot see system audit logs (sensitive admin activity logging)
5. **Import Data**: Staff cannot bulk import excel/CSV files (data integrity)
6. **Distribute Age Milestones**: Staff cannot process milestone bonus distributions (financial control)

### **Why These Restrictions Exist**:
- **Data Integrity**: Only admins can import, delete, or restore records
- **Financial Controls**: Only admins can distribute bonuses/milestones
- **Security Audit Trail**: Admins can track all system activity
- **User Privacy**: Staff cannot see who else has access to the system

---

## Migration Notes

If you previously had more complex permission systems, this simplified structure provides:

1. **Clear Role Definition**: No ambiguous permissions
2. **Consistent Default**: Staff gets "use system" access, admins get "manage system" access
3. **Easy Auditing**: All permissions traceable to constants and policies
4. **Scalability**: Can add more roles (e.g., "supervisor", "director") by extending this structure

---

## Examples

### Allow Staff to Restore Records
To give staff more permissions, update `UserRole::PERMISSIONS['staff']`:
```php
'restore_senior_citizens' => true,  // Change from false to true
```

### Create a "Read-Only Staff" Role
```php
public const READ_ONLY_STAFF = 'read-only-staff';

public const PERMISSIONS = [
    self::READ_ONLY_STAFF => [
        'view_senior_citizens' => true,
        'create_senior_citizens' => false,  // Cannot create
        'edit_senior_citizens' => false,    // Cannot edit
        // ... (mostly false)
    ],
];
```

### Custom Permission Check
```php
if ($authService->can($user, 'custom_permission')) {
    // Do something
}
```

---

## Troubleshooting

### "Unauthorized" Error When Staff Tries Action
1. Check the `UserRole::PERMISSIONS['staff']` array
2. Verify the action's policy method is registered
3. Ensure the policy is bound in `AppServiceProvider`

### New Feature Not Respecting Roles
1. Add permission key to `UserRole::PERMISSIONS` constant
2. Create/update policy method if needed
3. Use `@can` or `$this->authorize()` in views/controllers

---

## Future Enhancements

Potential improvements to this system:
1. **Dynamic Roles**: Store roles in database instead of constants
2. **Custom Permissions**: Allow per-user permission overrides
3. **Permission Groups**: Bundle related permissions (e.g., "manage_reports")
4. **Audit Trail**: Log all permission changes
5. **Role-Based Features**: Hide/show features based on role in UI
