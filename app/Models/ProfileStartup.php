<?php

namespace App\Models;
use App\Models\IndustryCategory;

class ProfileStartup extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'profile_startups';

    protected $primaryKey = 'startup_id';

    protected $fillable = [
        'startup_profile_str',
        'user_id',
        'startup_name',
        'startup_designation',
        'startup_mobile',
        'startup_email',
        'advmt_headline',
        'startup_intro',
        'name_of_entity',
        'business_type',
        'nature_of_entity',
        'estb_date',
        'emp_count',
        'signature',
        'industry_sector',
        'business_website',
        'facilities_desc',
        'annual_sales',
        'ebitda',
        'gross_profit',
        'inventory_value',
        'ebitda_margin',
        'rentals',
        'facebook_profile',
        'twitter_profile',
        'linkedin_profile',
        'ofc_address',
        'ofc_city',
        'ofc_state',
        'ofc_country',
        'ofc_pincode',
        'director_name',
        'director_email',
        'director_designation',
        'director_identification',
        'company_stage',
        'customer_problem',
        'product_service',
        'customer_segment',
        'target_market',
        'competitors',
        'competitive_advantage',
        'sales_marketing',
        'company_summary',
        'seeking_investors',
        'seeking_mentorship',
        'seeking_loan',
        'seeking_acquirers',
        'seeking_incubators',
        'business_pitch',
        'buyer_sell_price',
        'buyer_sell_reason',
        'inv_for',
        'inv_asking_price',
        'inv_stake',
        'inv_reason',
        'loan_collateral_details',
        'loan_amount',
        'loan_repayment_period',
        'loan_interest_rate',
        'loan_reason',
        'mentor_req_details',
        'accel_req_details',
        'accel_inv_req',
        'accel_time_period',
        'startup_prof_pic',
        'startup_prof_pic1',
        'startup_prof_thumb_pic',
        'startup_prof_thumb_pic1',
        'startup_doc_path',
        'startup_profile_status',
        'membership_paid',
        'membership_plan',
        'startup_prof_thumb_pic_name',
        'trackid',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'mailer_campaign',
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

    public function images()
    {
        return $this->hasMany(StartupImage::class, 'startup_id', 'startup_id');
    }

    public function management()
    {
        return $this->hasMany(ProfileStartupMgmt::class, 'startup_profile_id', 'startup_id');
    }

    public function fundRaising()
    {
        return $this->hasMany(ProfileStartupFundRaising::class, 'startup_profile_id', 'startup_id');
    }

    public function contactRequests()
    {
        return $this->hasMany(ContactStartup::class, 'profile_id', 'startup_id');
    }

    public function industrySector()
    {
        return $this->belongsTo(IndustryCategory::class, 'industry_sector', 'cat_id');
    }

}
