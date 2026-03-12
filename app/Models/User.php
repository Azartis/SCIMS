<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Determine if user is active.
     */
    public function isActive(): bool
    {
        // treat null (legacy rows) as active so they are not locked out
        return $this->status === 'active' || is_null($this->status);
    }

    /**
     * Determine if user is inactive or blocked.
     */
    public function isInactive(): bool
    {
        return in_array($this->status, ['inactive', 'blocked']);
    }

    /**
     * Scope to only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
