<?php
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\PresenceChannel;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    \Illuminate\Support\Facades\Log::info('User từ sanctum:', ['user' => $user]);
    if (!$user) return false;
    return $user->conversations()->where('conversations.id', $conversationId)->exists();
});

Broadcast::channel('presence.conversations', PresenceChannel::class);
