<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_STAFF = 'staff';

    /** Permission that gates entry to the admin panel. */
    public const PERMISSION_ACCESS_ADMIN = 'access-admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    /**
     * Whether the user holds an administrative role. Source of truth is Spatie
     * roles; `super_admin` bypasses all gates (see AppServiceProvider::boot).
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole([self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    /**
     * Whether the user may enter the admin panel at all. Gated on the
     * `access-admin` permission; `can()` returns false gracefully when the
     * permission is unknown or unassigned (never throws).
     */
    public function canManageContent(): bool
    {
        return $this->can(self::PERMISSION_ACCESS_ADMIN);
    }
}
