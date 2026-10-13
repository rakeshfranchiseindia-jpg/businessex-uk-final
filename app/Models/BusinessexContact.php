<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessexContact extends Model
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
