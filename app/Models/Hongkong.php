<?php

namespace App\Models;

class Hongkong extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'hongkong';

    protected $primaryKey = 'hk_id';

    protected $fillable = [
        'name',
        'email',
    ];

}
