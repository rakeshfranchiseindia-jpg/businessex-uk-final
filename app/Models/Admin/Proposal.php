<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proposal extends Model
{
    protected $table = 'profile_proposals';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'sender_user_id', 'user_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'recipient_user_id', 'user_id');
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}
