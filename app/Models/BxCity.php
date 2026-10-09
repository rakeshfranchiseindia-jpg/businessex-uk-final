<?php

namespace App\Models;

class BxCity extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_cities';


    protected $fillable = [
        'city',
        'state',
        'country',
    ];

}
