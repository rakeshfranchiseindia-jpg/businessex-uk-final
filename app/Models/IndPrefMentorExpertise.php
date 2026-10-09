<?php

namespace App\Models;

class IndPrefMentorExpertise extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'ind_pref_mentor_expertise';

    protected $primaryKey = 'mentor_ind_pref_id';

    protected $fillable = [
        'mentor_profile_id',
        'user_id',
        'parent_category_id',
        'sub_category_id',
        'profile_status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
