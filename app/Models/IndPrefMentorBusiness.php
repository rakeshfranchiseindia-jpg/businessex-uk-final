<?php

namespace App\Models;

class IndPrefMentorBusiness extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'ind_pref_mentor_business';

    protected $primaryKey = 'business_ind_pref_id';

    protected $fillable = [
        'business_profile_id',
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
