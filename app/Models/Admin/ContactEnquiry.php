<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ContactEnquiry extends Model
{
    protected $table = 'businessex_contactus';

    protected $primaryKey = 'contact_id';

    protected $guarded = ['contact_id'];
}
