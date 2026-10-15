<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationMessage extends Model
{
    protected $table = 'profile_contact_messages';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'sender_user_id', 'user_id');
    }
}
