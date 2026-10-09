<?php

namespace App\Models;

class ConversationReplyAttachment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'conversation_reply_attachment';


    protected $fillable = [
        'reply_id',
        'file_name',
        'file_size',
        'file_path',
        'is_active',
    ];


    public function reply()
    {
        return $this->belongsTo(ConversationReply::class, 'reply_id', 'id');
    }

}
