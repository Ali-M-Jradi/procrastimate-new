<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Group;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = \App\Models\Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'coach_id' => User::where('role', 'coach')->inRandomOrder()->first()->id ?? User::factory(),
            'admin_id' => User::where('role', 'admin')->inRandomOrder()->first()->id ?? User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'isCompleted' => $this->faker->boolean(30), // 30% chance completed
            'dueDate' => $this->faker->dateTimeBetween('now', '+1 month'),
            'group_id' => Group::inRandomOrder()->first()->id ?? Group::factory(),
        ];
    }
}
