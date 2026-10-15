<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncubatorProfile extends Model
{
    protected $table = 'profile_incubators';

    protected $primaryKey = 'incubator_id';

    protected $guarded = ['incubator_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'user_id');
    }
}
