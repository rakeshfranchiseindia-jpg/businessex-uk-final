<?php

namespace App\Models;

use App\Mail\ResetAccountPasswordEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;

class UserAccount extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_account';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_rand_id',
        'name',
        'email',
        'password',
        'mobile',
        'company_name',
        'is_active',
        'reg_profile',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function sendPasswordResetNotification($token): void
    {
        Mail::to($this->email)->queue(new ResetAccountPasswordEmail($this, $token));
    }
}
