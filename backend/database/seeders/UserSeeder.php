<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin, coach, and users
    
        User::factory()->create(['name' => 'Admin', 'email' => 'admin@example.com', 'role' => 'admin', 'password' => Hash::make('password')]);
        User::factory()->create(['name' => 'Coach', 'email' => 'coach@example.com', 'role' => 'coach', 'password' => Hash::make('password')]);
        User::factory(10)->create(['role' => 'user', 'password' => Hash::make('password')]);
    }
}
