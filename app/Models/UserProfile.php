<?php

namespace App\Models;

class UserProfile extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'user_profiles';

    protected $primaryKey = 'user_prof_id';

    protected $fillable = [
        'user_id',
        'profile_id',
        'profile_type',
        'profile_str',
        'profile_status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
