<?php

namespace App\Models;

class ProfileBroker extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'profile_broker';

    protected $primaryKey = 'broker_id';

    protected $fillable = [
        'broker_profile_str',
        'user_id',
        'broker_name',
        'broker_mobile',
        'broker_email',
        'broker_profile_type',
        'broker_company',
        'estb_year',
        'emp_count',
        'company_city',
        'company_state',
        'company_country',
        'company_website',
        'ofc_city',
        'ofc_state',
        'ofc_country',
        'ofc_pincode',
        'prof_summary',
        'prof_exp_year',
        'broker_company_logo',
        'broker_profile_status',
        'membership_paid',
        'membership_plan',
        'contact_response',
        'utm_source',
        'contact_status',
        'activated_by',
        'activated_at',
        'last_login_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function industryPreferences()
    {
        return $this->hasMany(IndPrefBroker::class, 'broker_profile_id', 'broker_id');
    }

    public function locationPreferences()
    {
        return $this->hasMany(LocPrefBroker::class, 'broker_profile_id', 'broker_id');
    }

    public function contactRequests()
    {
        return $this->hasMany(ContactBroker::class, 'profile_id', 'broker_id');
    }

}
