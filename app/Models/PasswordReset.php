<?php

namespace App\Models;

class PasswordReset extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'password_resets';


    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'token',
    ];

}
