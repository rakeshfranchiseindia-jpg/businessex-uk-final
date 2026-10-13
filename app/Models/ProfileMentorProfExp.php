<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileMentorProfExp extends Model
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
