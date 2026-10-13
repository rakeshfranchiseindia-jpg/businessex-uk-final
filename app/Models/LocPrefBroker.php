<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocPrefBroker extends Model
{
    protected $table = 'loc_pref_broker';

    protected $primaryKey = 'broker_loc_id';

    protected $fillable = [
        'broker_profile_id',
        'user_id',
        'location_name',
        'loc_state',
        'loc_country',
        'profile_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
