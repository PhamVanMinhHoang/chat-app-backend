<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    protected $model = Conversation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isGroup = $this->faker->boolean(30);
        return [
            'type' => $isGroup ? 'group' : 'private',
            'name' => $isGroup ? $this->faker->words(3, true) : null,
            'avatar' => null,
        ];
    }
}
