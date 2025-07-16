<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ConversationRepositoryInterface;

class ConversationRepository implements  ConversationRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = app()->make('App\Models\Conversation');
    }

    public function getAllConversations()
    {
        return $this->model->get();
    }

    public function getConversationById(int $conversationId)
    {
        return $this->model->where('id', $conversationId)->first();
    }

    public function createConversation(array $data)
    {
        return $this->model->create($data);
    }

    public function updateConversation(int $conversationId, array $data)
    {
        $conversation = $this->getConversationById($conversationId);
        if ($conversation) {
            return $conversation->update($data);
        }
        return false;
    }

    public function deleteConversation(int $conversationId)
    {
        $conversation = $this->getConversationById($conversationId);
        if ($conversation) {
            return $conversation->delete();
        }
        return false;
    }
}
