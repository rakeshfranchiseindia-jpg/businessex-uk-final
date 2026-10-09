<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileMentor extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;

    protected $table = 'profile_mentors';

    protected $primaryKey = 'mentor_id';

    protected $fillable = [
        'mentor_profile_str',
        'user_id',
        'mentor_name',
        'mentor_mobile',
        'mentor_email',
        'mentor_location',
        'mentor_city',
        'mentor_state',
        'mentor_country',
        'mentor_adv_headline',
        'mentor_intro',
        'mentor_occupation',
        'mentor_company',
        'mentor_designation',
        'mentor_profile_summary',
        'mentor_profile_pic',
        'mentor_linkedin',
        'mentor_profile_status',
        'membership_paid',
        'membership_plan',
        'mentor_profile_pic_name',
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
        return $this->hasMany(ProfileMentorProfExp::class, 'mentor_profile_id', 'mentor_id');
    }

    public function expertise()
    {
        return $this->hasMany(MentorExpertise::class, 'mentor_id', 'mentor_id');
    }

    public function industryPreferences()
    {
        return $this->hasMany(IndPrefMentor::class, 'mentor_profile_id', 'mentor_id');
    }

    public function contactRequests()
    {
        return $this->hasMany(ContactMentor::class, 'profile_id', 'mentor_id');
    }

}
