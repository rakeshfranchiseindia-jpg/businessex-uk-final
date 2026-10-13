<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BxCity extends Model
{
    protected $table = 'bx_cities';

    protected $fillable = [
        'city',
        'state',
        'country',
    ];
}
