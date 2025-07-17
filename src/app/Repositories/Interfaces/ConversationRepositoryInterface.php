<?php

namespace App\Repositories\Interfaces;

interface ConversationRepositoryInterface
{
    /**
     * Get all conversations for a specific user.
     */
    public function getAllConversations(int $userId);

    /**
     * Get a conversation by id that belongs to a specific user.
     */
    public function getConversationById(int $conversationId, int $userId);

    public function createConversation(array $data);

    public function updateConversation(int $conversationId, array $data);

    public function deleteConversation(int $conversationId);
}
