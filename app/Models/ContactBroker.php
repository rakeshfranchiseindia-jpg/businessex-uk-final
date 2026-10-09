<?php

namespace App\Models;

class ContactBroker extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'contact_broker';

    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'profile_str',
        'user_id',
        'profile_id',
        'contact_name',
        'contact_designation',
        'contact_mobile',
        'contact_email',
        'contact_company',
        'contact_investment',
        'contact_comment',
        'contact_viewed',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
