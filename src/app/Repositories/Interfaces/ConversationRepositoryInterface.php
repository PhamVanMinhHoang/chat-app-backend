<?php

namespace App\Repositories\Interfaces;

interface ConversationRepositoryInterface
{
    public function getAllConversations($user);

    public function getConversationById(int $conversationId);

    public function createConversation(array $data);

    public function updateConversation(int $conversationId, array $data);

    public function deleteConversation(int $conversationId);
    public function getPrivateConversation(int $userId, int $otherUserId);
}
