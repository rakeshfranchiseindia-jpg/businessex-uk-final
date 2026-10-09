<?php

namespace App\Models;

class ProfileIncubatorProfExp extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'profile_incubator_prof_exp';

    protected $primaryKey = 'incubator_mgmt_id';

    protected $fillable = [
        'incubator_profile_id',
        'user_id',
        'exp_year',
        'exp_sector',
    ];


    public function incubatorProfile()
    {
        return $this->belongsTo(ProfileIncubator::class, 'incubator_profile_id', 'incubator_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
