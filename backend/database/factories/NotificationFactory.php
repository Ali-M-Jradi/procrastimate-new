<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'to_user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'message' => $this->faker->sentence,
            'read' => $this->faker->boolean(20), // 20% chance it's read
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),

        ];
    }
}
