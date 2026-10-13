<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessexNewsletter extends Model
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
