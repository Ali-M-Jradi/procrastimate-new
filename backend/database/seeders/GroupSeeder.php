<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::factory(5)->create();
        // Attach users to groups
        $groups = Group::all();
        $users = User::all();
        foreach ($groups as $group) {
            $group->users()->attach($users->random(rand(2, 5))->pluck('id')->toArray());
        }
    }
}
