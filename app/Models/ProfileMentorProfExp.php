<?php

namespace App\Models;

class ProfileMentorProfExp extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'profile_mentor_prof_exp';

    protected $primaryKey = 'mentor_mgmt_id';

    protected $fillable = [
        'mentor_profile_id',
        'user_id',
        'exp_year',
        'exp_sector',
    ];


    public function mentorProfile()
    {
        return $this->belongsTo(ProfileMentor::class, 'mentor_profile_id', 'mentor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
