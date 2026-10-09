<?php

namespace App\Models;

class ProfileStartupMgmt extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'profile_startup_mgmt';

    protected $primaryKey = 'startup_mgmt_id';

    protected $fillable = [
        'startup_profile_id',
        'user_id',
        'mgmt_name',
        'mgmt_designation',
        'mgmt_email',
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
