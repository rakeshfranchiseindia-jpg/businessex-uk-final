<?php

namespace App\Models;

class BxService extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_services';

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_no',
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'designation',
        'event_city',
        'event_date',
        'event_timing',
        'event_topic',
        'is_member',
        'amount',
        'service_type',
        'product_details',
        'udf',
        'payment_status',
        'payment_mode',
        'contact_response',
        'contact_status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

}
