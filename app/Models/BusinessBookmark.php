<?php

namespace App\Models;

class BusinessBookmark extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'business_bookmarks';

    protected $primaryKey = 'bookmark_id';

    protected $fillable = [
        'user_id',
        'profile_id',
        'profile_type',
        'profile_str',
        'bookmark_status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
