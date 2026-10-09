<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileLender extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;

    protected $table = 'profile_lenders';

    protected $primaryKey = 'lender_id';

    protected $fillable = [
        'lender_profile_str',
        'user_id',
        'lender_name',
        'lender_mobile',
        'lender_email',
        'lender_location',
        'lender_city',
        'lender_state',
        'lender_country',
        'lender_adv_headline',
        'lender_intro',
        'lender_type',
        'lender_occupation',
        'lending_capacity',
        'lending_interest_rate',
        'loan_offerings',
        'prof_summary',
        'nbfc_contact_name',
        'nbfc_contact_designation',
        'nbfc_comp_name',
        'nbfc_type',
        'nbfc_branch',
        'nbfc_country',
        'nbfc_state',
        'nbfc_city',
        'nbfc_pincode',
        'nbfc_website',
        'nbfc_about',
        'profile_pic_path',
        'nbfc_corporate_profile_path',
        'rbi_registered',
        'rbi_registered_no',
        'lender_profile_status',
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
        'lending_capacity' => 'decimal:2',
        'activated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function industryPreferences()
    {
        return $this->hasMany(IndPrefLender::class, 'lender_profile_id', 'lender_id');
    }

    public function locationPreferences()
    {
        return $this->hasMany(LocPrefLender::class, 'lender_profile_id', 'lender_id');
    }

    public function contactRequests()
    {
        return $this->hasMany(ContactLender::class, 'profile_id', 'lender_id');
    }

}
