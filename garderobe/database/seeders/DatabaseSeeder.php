<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ClothingItemSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in the correct order to maintain dependencies
        $this->call([
            UserSeeder::class,         // First create users
            CategorySeeder::class,     // Then create categories
            ClothingItemSeeder::class, // Finally create clothing items
        ]);
    }
}
