<?php

namespace App\Models;

class ConversationReply extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'conversation_reply';


    protected $fillable = [
        'reply',
        'from_id',
        'to_id',
        'timestamp',
        'request_id',
        'readstatus',
    ];


    public function from()
    {
        return $this->belongsTo(User::class, 'from_id', 'user_id');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(ConversationReplyAttachment::class, 'reply_id', 'id');
    }

}
