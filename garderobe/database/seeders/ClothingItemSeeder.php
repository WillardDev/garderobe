<?php

namespace Database\Seeders;

use App\Models\ClothingItem;
use App\Models\User;
use App\Models\Category;
use Database\Factories\ClothingItemFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ClothingItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset the used images tracking in the factory
        ClothingItemFactory::resetUsedImages();
        
        // Count available images in the clothes folder
        $localImages = Storage::disk('public')->files('clothes');
        $imageFiles = array_filter($localImages, function($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        $imageCount = count($imageFiles);
        
        // Generate exactly as many clothing items as there are images
        ClothingItem::factory($imageCount)->create();
        
        $this->command->info("Created $imageCount clothing items, one for each unique image in the clothes folder.");
    }
}
