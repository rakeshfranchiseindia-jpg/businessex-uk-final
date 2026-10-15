<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstantResponse extends Model
{
    protected $table = 'profile_instant_responses';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $guarded = ['user_id'];

    protected $casts = [
        'enabled' => 'boolean',
        'locked_by_admin' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'user_id');
    }
}
