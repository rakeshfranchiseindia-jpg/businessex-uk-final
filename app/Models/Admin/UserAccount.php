<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAccount extends Model
{
    protected $table = 'user_account';

    protected $primaryKey = 'user_id';

    protected $guarded = ['user_id'];

    protected $hidden = [
        'password',
        'remember_token',
        'verify_token',
        'reset_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(UserProfile::class, 'user_id', 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OnlinePayment::class, 'user_id', 'user_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'user_id', 'user_id');
    }
}
