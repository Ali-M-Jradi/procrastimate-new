<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $coach = User::where('role', 'coach')->inRandomOrder()->first();
        $admin = User::where('role', 'admin')->inRandomOrder()->first();

        foreach ($users as $user) {
            $groupId = $user->groups->isNotEmpty() ? $user->groups->random()->id : null;

            Task::factory(5)->create([
                'user_id' => $user->id,
                'coach_id' => $coach ? $coach->id : null,
                'admin_id' => $admin ? $admin->id : null,
            ]);
        }
    }
}
