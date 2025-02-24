<?php

namespace Database\Seeders;

use App\Models\ClothingItem;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ClothingItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 50 clothing items using the factory
        ClothingItem::factory(50)->create();
    }
}
