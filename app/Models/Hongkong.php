<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hongkong extends Model
{
    protected $table = 'hongkong';

    protected $primaryKey = 'hk_id';

    protected $fillable = [
        'name',
        'email',
    ];
}
