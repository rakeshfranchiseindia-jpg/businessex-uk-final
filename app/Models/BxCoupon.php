<?php

namespace App\Models;

class BxCoupon extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_coupon';


    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount_amount',
        'user_type',
        'profile_type',
        'membership',
        'start_date',
        'end_date',
        'max_redemption',
        'redemption_number',
        'platform',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

}
