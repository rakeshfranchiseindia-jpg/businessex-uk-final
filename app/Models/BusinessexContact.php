<?php

namespace App\Models;

class BusinessexContact extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'businessex_contactus';

    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_mobile',
        'contact_comment',
    ];

}
