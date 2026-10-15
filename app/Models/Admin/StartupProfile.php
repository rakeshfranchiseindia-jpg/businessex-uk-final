<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupProfile extends Model
{
    protected $table = 'profile_startups';

    protected $primaryKey = 'startup_id';

    protected $guarded = ['startup_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'user_id');
    }
}
