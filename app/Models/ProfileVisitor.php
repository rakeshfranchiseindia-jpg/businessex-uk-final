<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileVisitor extends Model
{
    protected $table = 'profile_visitors';

    protected $primaryKey = 'visitor_id';

    protected $fillable = [
        'visitor_ip',
        'profile_id',
        'user_id',
        'profile_type',
        'profile_str',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
