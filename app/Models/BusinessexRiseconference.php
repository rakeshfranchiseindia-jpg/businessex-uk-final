<?php

namespace App\Models;

class BusinessexRiseconference extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'businessex_riseconference';

    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'ref_type',
        'contact_name',
        'contact_last',
        'contact_email',
        'contact_mobile',
        'contact_company',
        'contact_country',
    ];

}
