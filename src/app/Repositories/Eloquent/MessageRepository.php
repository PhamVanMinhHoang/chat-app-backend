<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\MessageRepositoryInterface;

class MessageRepository implements MessageRepositoryInterface
{
    protected $model;
    /**
     * MessageRepository constructor.
     */
    public function __construct()
    {
        $this->model = app()->make('App\Models\Message'); // Assuming you have a Message model
    }
    /**
     * Get messages by conversation ID.
     *
     * @param int $conversationId
     * @param int $limit
     * @return mixed
     */
    public function getMessagesByConversationId(int $conversationId, int $limit = 50)
    {
        return $this->model->where('conversation_id', $conversationId)->paginate($limit);
    }

    /**
     * Create a new message.
     *
     * @param array $data
     * @return mixed
     */
    public function createMessage(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing message.
     *
     * @param int $messageId
     * @param array $data
     * @return mixed
     */
    public function updateMessage(int $messageId, array $data)
    {
        return $this->model->where('id', $messageId)->update($data);
    }

    /**
     * Delete a message.
     *
     * @param int $messageId
     * @return mixed
     */
    public function deleteMessage(int $messageId)
    {
        return $this->model->destroy($messageId);
    }
}
