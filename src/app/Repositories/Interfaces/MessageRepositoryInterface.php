<?php

namespace App\Repositories\Interfaces;

interface MessageRepositoryInterface
{
    /**
     * Get messages by conversation ID.
     *
     * @param int $conversationId
     * @param int $limit
     * @return mixed
     */
    public function getMessagesByConversationId(int $conversationId, int $limit = 50);

    /**
     * Create a new message.
     *
     * @param array $data
     * @return mixed
     */
    public function createMessage(array $data);

    /**
     * Update an existing message.
     *
     * @param int $messageId
     * @param array $data
     * @return mixed
     */
    public function updateMessage(int $messageId, array $data);

    /**
     * Delete a message.
     *
     * @param int $messageId
     * @return mixed
     */
    public function deleteMessage(int $messageId);
}
