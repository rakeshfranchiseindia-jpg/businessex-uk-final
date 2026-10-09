<?php

namespace App\Models;

class ProfileMembership extends \Illuminate\Database\Eloquent\Model
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
        'activation_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
