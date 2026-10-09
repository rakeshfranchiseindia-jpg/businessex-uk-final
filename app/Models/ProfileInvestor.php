<?php

namespace App\Models;

class ProfileInvestor extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'profile_investor';

    protected $primaryKey = 'investor_id';

    protected $fillable = [
        'inv_profile_str',
        'user_id',
        'inv_name',
        'inv_email',
        'inv_mobile',
        'inv_placeid',
        'inv_city',
        'inv_state',
        'inv_country',
        'inv_headline',
        'inv_intro',
        'inv_type',
        'firm_type',
        'invest_size_min',
        'invest_size_max',
        'invest_pref',
        'invest_stake',
        'full_acquisition',
        'purchase_capacity_min',
        'purchase_capacity_max',
        'inv_abt_urself',
        'linkedin_profile',
        'company_name',
        'company_designation',
        'company_placeid',
        'company_city',
        'company_state',
        'company_country',
        'company_pincode',
        'company_website',
        'company_logo_path',
        'company_summary',
        'inv_profile_pic_path',
        'inv_profile_status',
        'membership_paid',
        'membership_plan',
        'inv_profile_pic_name',
        'trackid',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'contact_response',
        'contact_status',
        'activated_by',
        'activated_at',
        'last_login_at',
        'reg_source',
    ];

    protected $casts = [
        'invest_size_min' => 'decimal:2',
        'invest_size_max' => 'decimal:2',
        'purchase_capacity_min' => 'decimal:2',
        'purchase_capacity_max' => 'decimal:2',
        'activated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function industryPreferences()
    {
        return $this->hasMany(IndPrefInvestor::class, 'investor_profile_id', 'investor_id');
    }

    public function locationPreferences()
    {
        return $this->hasMany(LocPrefInvestor::class, 'investor_profile_id', 'investor_id');
    }

    public function contactRequests()
    {
        return $this->hasMany(ContactInvestor::class, 'profile_id', 'investor_id');
    }

}
