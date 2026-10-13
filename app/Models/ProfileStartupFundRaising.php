<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileStartupFundRaising extends Model
{
    protected $table = 'profile_startup_fund_raising';

    protected $primaryKey = 'startup_fund_id';

    protected $fillable = [
        'startup_profile_id',
        'user_id',
        'fund_stage',
        'fund_amount',
    ];

    public function startupProfile()
    {
        return $this->belongsTo(ProfileStartup::class, 'startup_profile_id', 'startup_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
