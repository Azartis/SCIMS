<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Only admins can manage users
     */
    public function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}
