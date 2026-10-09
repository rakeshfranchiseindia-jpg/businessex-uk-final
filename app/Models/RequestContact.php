<?php

namespace App\Models;

class RequestContact extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'request_contact';

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'profile_str',
        'receiver',
        'sender',
        'receiver_profile_type',
        'sender_profile_type',
        'status',
        'viewed_status',
        'msg',
        'timestamp',
    ];


    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver', 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender', 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(ConversationReply::class, 'request_id', 'request_id');
    }

}
