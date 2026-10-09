<?php

namespace App\Models;

class Job extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'jobs';


    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'reserved_at',
        'available_at',
    ];

}
