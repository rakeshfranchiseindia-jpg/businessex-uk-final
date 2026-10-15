<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $table = 'profile_contact_conversations';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'owner_user_id', 'user_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'sender_user_id', 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class, 'conversation_id')->orderBy('created_at');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'conversation_id');
    }
}
