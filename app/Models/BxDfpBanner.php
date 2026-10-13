<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BxDfpBanner extends Model
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
