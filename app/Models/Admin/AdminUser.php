<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $table = 'admin_user';

    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'admin_name',
        'admin_email',
        'admin_password',
        'admin_dept',
        'admin_role',
        'admin_is_active',
    ];

    protected $hidden = [
        'admin_password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'admin_role' => 'integer',
            'admin_is_active' => 'integer',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'admin_password';
    }

    public function isSuperAdmin(): bool
    {
        return $this->admin_role === 1;
    }

    /**
     * Whether this admin may open an area defined in config('admin.permissions').
     */
    public function canAccess(string $area): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($this->admin_role, config("admin.permissions.{$area}", []), true);
    }

    public function roleName(): string
    {
        return config('admin.roles')[$this->admin_role] ?? 'Admin';
    }
}
