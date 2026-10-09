<?php

namespace App\Models;

class BxDfpBanner extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_dfp_banner';


    protected $fillable = [
        'dfp_id',
        'dfp_slot',
        'page',
        'location',
        'width',
        'height',
        'is_active',
    ];

}
