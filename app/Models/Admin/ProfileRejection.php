<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProfileRejection extends Model
{
    protected $table = 'admin_profile_rejected';

    protected $primaryKey = 'prof_reject_id';

    protected $guarded = ['prof_reject_id'];
}
