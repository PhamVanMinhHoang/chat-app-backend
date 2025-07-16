<?php

namespace Database\Factories;

use App\Models\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserStatus>
 */
class UserStatusFactory extends Factory
{
    protected $model = UserStatus::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['online', 'offline']),
            'last_seen' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
