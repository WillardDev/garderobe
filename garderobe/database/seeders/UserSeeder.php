<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user with known credentials
        User::factory()->create([
            'name' => 'Jack Admin',
            'email' => 'jack@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Create 9 more random users
        User::factory(9)->create();
    }
}
