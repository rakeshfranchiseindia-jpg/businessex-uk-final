<?php

namespace App\Models;

use App\Mail\ResetAccountPasswordEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

    /**
     * Ends every stored session for this account (all devices/browsers).
     * Only the database session driver keeps sessions addressable by user.
     */
    public function terminateSessions(): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', $this->getKey())
            ->delete();
    }

    public function sendPasswordResetNotification($token): void
    {
        Mail::to($this->email)->queue(new ResetAccountPasswordEmail($this, $token));
    }
}
