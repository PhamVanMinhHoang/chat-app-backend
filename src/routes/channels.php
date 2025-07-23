<?php
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\PresenceChannel;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    if (!$user) return false;
    // Trả về true nếu user thuộc cuộc trò chuyện, ngược lại là false
    $isMember = $user->conversations()->where('conversations.id', $conversationId)->exists();
    if (!$isMember) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});


Broadcast::channel('presence.conversations', PresenceChannel::class);
