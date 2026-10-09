<?php

namespace App\Models;

class AdminUser extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'admin_user';

    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'admin_name',
        'admin_email',
        'admin_password',
        'admin_dept',
        'admin_role',
        'admin_is_active',
    ];

    protected $hidden = [
        'admin_password',
    ];

}
