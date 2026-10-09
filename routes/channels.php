<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{conversationId}', function ($user, int $conversationId): bool {
    return DB::table('profile_contact_conversations')
        ->where('id', $conversationId)
        ->where(function ($query) use ($user): void {
            $query->where('owner_user_id', $user->user_id)
                ->orWhere('sender_user_id', $user->user_id);
        })
        ->exists();
});
