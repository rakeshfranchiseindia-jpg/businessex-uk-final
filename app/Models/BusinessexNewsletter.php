<?php

namespace App\Models;

class BusinessexNewsletter extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'businessex_newsletter';

    protected $primaryKey = 'newsletter_id';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'city',
        'status',
        'unsubscribe_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
