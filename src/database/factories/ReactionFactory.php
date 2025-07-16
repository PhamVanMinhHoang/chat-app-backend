<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    protected $model = Reaction::class;

    protected static $types = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];

    public function definition()
    {
        return [
            'message_id' => 1, // placeholder, actual set in seeder
            'user_id' => 1,
            'reaction_type' => $this->faker->randomElement(self::$types),
        ];
    }
}
