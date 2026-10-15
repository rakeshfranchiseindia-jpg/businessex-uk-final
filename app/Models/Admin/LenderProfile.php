<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LenderProfile extends Model
{
    protected $table = 'profile_lenders';

    protected $primaryKey = 'lender_id';

    protected $guarded = ['lender_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'user_id');
    }
}
