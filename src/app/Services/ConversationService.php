<?php

namespace App\Services;

use App\Repositories\Interfaces\ConversationRepositoryInterface;

class ConversationService
{
    protected ConversationRepositoryInterface $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function getAllConversations(int $userId)
    {
        return $this->conversationRepository->getAllConversations($userId);
    }

    public function getConversationById(int $conversationId, int $userId)
    {
        return $this->conversationRepository->getConversationById($conversationId, $userId);
    }

    public function createConversation(array $data)
    {
        return $this->conversationRepository->createConversation($data);
    }

    public function updateConversation(int $conversationId, array $data)
    {
        return $this->conversationRepository->updateConversation($conversationId, $data);
    }

    public function deleteConversation(int $conversationId)
    {
        return $this->conversationRepository->deleteConversation($conversationId);
    }
}
