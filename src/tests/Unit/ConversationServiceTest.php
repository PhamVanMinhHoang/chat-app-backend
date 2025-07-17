<?php

namespace Tests\Unit;

use App\Repositories\Interfaces\ConversationRepositoryInterface;
use App\Services\ConversationService;
use Mockery;
use PHPUnit\Framework\TestCase;

class ConversationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_conversations_passes_user_id_to_repository(): void
    {
        $repo = Mockery::mock(ConversationRepositoryInterface::class);
        $service = new ConversationService($repo);

        $userId = 1;
        $expected = ['conversation1', 'conversation2'];

        $repo->shouldReceive('getAllConversations')
            ->once()
            ->with($userId)
            ->andReturn($expected);

        $this->assertSame($expected, $service->getAllConversations($userId));
    }
}
