<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileMembership extends Model
{
    protected $table = 'profile_memberships';

    protected $primaryKey = 'membership_id';

    protected $fillable = [
        'user_id',
        'profile_type',
        'profile_id',
        'order_no',
        'amount',
        'membership_type',
        'payment_source',
        'payment_comments',
        'interaction_credits',
        'instant_responses',
        'activation_date',
        'expiry_date',
        'is_active',
        'upg_source',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'profile_type' => 'integer',
        'profile_id' => 'integer',
        'activation_date' => 'datetime',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'user_id');
    }
}
