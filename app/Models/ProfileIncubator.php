<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileIncubator extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;

    protected $table = 'profile_incubators';

    protected $primaryKey = 'incubator_id';

    protected $fillable = [
        'incubator_profile_str',
        'user_id',
        'incubator_name',
        'incubator_mobile',
        'incubator_email',
        'incubator_location',
        'incubator_city',
        'incubator_state',
        'incubator_country',
        'incubator_adv_headline',
        'incubator_intro',
        'incubator_company',
        'incubator_designation',
        'incubator_profile_summary',
        'incubator_company_logo',
        'estb_year',
        'company_city',
        'company_state',
        'company_country',
        'company_pincode',
        'signature',
        'company_website',
        'membership_paid',
        'membership_plan',
        'incubator_profile_status',
        'trackid',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'contact_response',
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

    public function experience()
    {
        return $this->hasMany(ProfileIncubatorProfExp::class, 'incubator_profile_id', 'incubator_id');
    }

    public function businessPreferences()
    {
        return $this->hasMany(IndPrefIncubatorBusiness::class, 'business_profile_id', 'incubator_id');
    }

    public function expertisePreferences()
    {
        return $this->hasMany(IndPrefIncubatorExpertise::class, 'incubator_profile_id', 'incubator_id');
    }

    public function startupPreferences()
    {
        return $this->hasMany(IndPrefIncubatorStartup::class, 'startup_profile_id', 'incubator_id');
    }

    public function contactRequests()
    {
        return $this->hasMany(ContactIncubator::class, 'profile_id', 'incubator_id');
    }

}
