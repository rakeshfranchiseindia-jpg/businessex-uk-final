<?php

namespace App\Models;

class LocPrefIncubator extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'loc_pref_incubators';

    protected $primaryKey = 'incubator_loc_id';

    protected $fillable = [
        'incubator_profile_id',
        'user_id',
        'place_id',
        'location_name',
        'loc_state',
        'loc_country',
        'loc_latitude',
        'loc_longitude',
        'profile_status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
