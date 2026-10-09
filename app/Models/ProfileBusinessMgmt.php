<?php

namespace App\Models;

class ProfileBusinessMgmt extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'profile_business_mgmt';

    protected $primaryKey = 'business_mgmt_id';

    protected $fillable = [
        'business_profile_id',
        'user_id',
        'mgmt_name',
        'mgmt_designation',
        'mgmt_email',
    ];


    public function businessProfile()
    {
        return $this->belongsTo(ProfileBusiness::class, 'business_profile_id', 'business_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
