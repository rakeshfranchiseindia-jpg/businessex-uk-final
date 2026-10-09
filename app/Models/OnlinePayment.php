<?php

namespace App\Models;

class OnlinePayment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'online_payments';

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_no',
        'user_id',
        'profile_type',
        'profile_id',
        'coupon_id',
        'name',
        'email',
        'phone',
        'city',
        'country',
        'product_details',
        'membership_plan',
        'amount',
        'udf',
        'payment_status',
        'payment_mode',
        'addon_one',
        'addon_two',
        'addon_three',
        'addon_four',
        'addon_five',
        'addon_six',
        'status_message',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function coupon()
    {
        return $this->belongsTo(BxCoupon::class, 'coupon_id', 'id');
    }

}
