<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ConversationRepositoryInterface;

class ConversationRepository implements ConversationRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = app()->make('App\Models\Conversation');
    }

    public function getAllConversations(int $userId)
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    public function getConversationById(int $conversationId, int $userId)
    {
        return $this->model->where('id', $conversationId)
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })->first();
    }

    public function createConversation(array $data)
    {
        return $this->model->create($data);
    }

    public function updateConversation(int $conversationId, array $data)
    {
        $conversation = $this->model->where('id', $conversationId)->first();
        if ($conversation) {
            return $conversation->update($data);
        }
        return false;
    }

    public function deleteConversation(int $conversationId)
    {
        $conversation = $this->model->where('id', $conversationId)->first();
        if ($conversation) {
            return $conversation->delete();
        }
        return false;
    }
}
