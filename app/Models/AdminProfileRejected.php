<?php

namespace App\Models;

class AdminProfileRejected extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'admin_profile_rejected';

    protected $primaryKey = 'prof_reject_id';

    protected $fillable = [
        'profile_type',
        'profile_id',
        'admin_email',
        'rejected_reason',
    ];

}
