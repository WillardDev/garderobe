<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClothingItem>
 */
class ClothingItemFactory extends Factory
{
    /**
     * Keep track of images that have already been used
     *
     * @var array
     */
    protected static $usedImages = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define arrays of realistic values for clothing items
        $brands = [
            'Nike', 'Adidas', 'H&M', 'Zara', 'Levi\'s', 'Gap', 'Uniqlo', 
            'Calvin Klein', 'Ralph Lauren', 'North Face', 'Patagonia', 
            'Gucci', 'Prada', 'Versace', 'Armani', 'Balenciaga'
        ];
        
        $colors = [
            'Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Purple', 
            'Pink', 'Brown', 'Gray', 'Navy', 'Beige', 'Khaki', 'Maroon', 
            'Olive', 'Turquoise', 'Indigo', 'Teal', 'Coral'
        ];
        
        $sizes = [
            'XS', 'S', 'M', 'L', 'XL', 'XXL', '30', '32', '34', '36', '38', 
            '40', '42', '44', '6', '7', '8', '9', '10', '11', '12', 'One Size'
        ];
        
        // Get random clothing item name based on category
        $categoryItems = [
            'Tops' => ['T-Shirt', 'Blouse', 'Dress Shirt', 'Polo', 'Sweater', 'Hoodie', 'Tank Top', 'Cardigan'],
            'Bottoms' => ['Jeans', 'Chinos', 'Shorts', 'Skirt', 'Dress Pants', 'Leggings', 'Joggers', 'Culottes'],
            'Outerwear' => ['Jacket', 'Coat', 'Blazer', 'Parka', 'Windbreaker', 'Raincoat', 'Puffer Jacket'],
            'Footwear' => ['Sneakers', 'Boots', 'Flats', 'Heels', 'Sandals', 'Loafers', 'Oxfords', 'Slippers'],
            'Accessories' => ['Hat', 'Belt', 'Scarf', 'Gloves', 'Socks', 'Tie', 'Watch', 'Sunglasses', 'Bag'],
            'Formal Wear' => ['Suit', 'Dress', 'Tuxedo', 'Gown', 'Formal Shirt', 'Formal Pants', 'Tie'],
            'Activewear' => ['Running Shorts', 'Sports Bra', 'Athletic Shirt', 'Leggings', 'Track Pants', 'Swimsuit'],
            'Sleepwear' => ['Pajamas', 'Nightgown', 'Robe', 'Sleep Shirt', 'Sleep Pants', 'Slippers']
        ];

        // Get a random category ID that exists in the database
        $category = Category::inRandomOrder()->first();
        $categoryName = $category ? $category->name : 'Tops';
        
        // STEP 1: Get all images from the storage folder
        $localImages = Storage::disk('public')->files('clothes');
        
        // Filter to only include image files
        $localImages = array_filter($localImages, function($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        // Check if we have any images
        if (empty($localImages)) {
            throw new \Exception('No images found in storage/app/public/clothes directory');
        }
        
        // STEP 2: Filter out images that have already been used
        $availableImages = array_diff($localImages, self::$usedImages);
        
        // If no available images, throw an exception (all images have been used)
        if (empty($availableImages)) {
            throw new \Exception('All images have been used. No more unique images available for seeding.');
        }
        
        // Select a random image from the remaining available images
        $selectedImagePath = $this->faker->randomElement($availableImages);
        
        // Mark this image as used
        self::$usedImages[] = $selectedImagePath;
        
        $filename = pathinfo($selectedImagePath, PATHINFO_FILENAME);
        
        // STEP 3: Extract item type from filename
        // Convert underscores/hyphens to spaces and clean up the filename
        $cleanFilename = str_replace(['-', '_'], ' ', $filename);
        $cleanFilename = preg_replace('/[0-9]+/', '', $cleanFilename); // Remove numbers
        $cleanFilename = trim($cleanFilename);
        
        // STEP 4: Determine the item type and category based on the image filename
        $detectedItemType = null;
        $detectedCategory = null;
        
        // Search through all categories and their items to find a match
        foreach ($categoryItems as $catName => $items) {
            foreach ($items as $item) {
                if (stripos($cleanFilename, strtolower($item)) !== false) {
                    $detectedItemType = $item;
                    $detectedCategory = $catName;
                    break 2; // Break both loops
                }
            }
        }
        
        // If we couldn't determine the item type from filename, use one from the category
        if (!$detectedItemType) {
            $itemTypes = $categoryItems[$categoryName] ?? ['Clothing Item'];
            $detectedItemType = $this->faker->randomElement($itemTypes);
        }
        
        // STEP 5: Generate the item name using brand and detected item type
        $itemFullName = $this->faker->randomElement($brands) . ' ' . $detectedItemType;
        
        // Create a unique filename for storing
        $sanitizedItemName = preg_replace('/[^a-z0-9]+/', '-', strtolower($itemFullName));
        $uniqueFilename = $sanitizedItemName . '-' . substr($this->faker->uuid, 0, 8) . '.' . pathinfo($selectedImagePath, PATHINFO_EXTENSION);
        $destinationPath = 'images/' . $uniqueFilename;
        
        // Copy the file to the image storage location
        if (Storage::disk('public')->exists($selectedImagePath)) {
            $imageContents = Storage::disk('public')->get($selectedImagePath);
            Storage::disk('images')->put($destinationPath, $imageContents);
        }

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'category_id' => $detectedCategory && $category && $detectedCategory == $categoryName 
                ? $category->id 
                : Category::where('name', $detectedCategory)->first()->id ?? $category->id,
            'name' => $itemFullName,
            'description' => $this->faker->sentence(6, true),
            'color' => $this->faker->randomElement($colors),
            'size' => $this->faker->randomElement($sizes),
            'brand' => $this->faker->randomElement($brands),
            'image_path' => $destinationPath,
        ];
    }
    
    /**
     * Get a random fashion-appropriate color hex code
     *
     * @return string
     */
    private function getColorHex(): string
    {
        $fashionColors = [
            '000000', // Black
            'FFFFFF', // White
            'FF0000', // Red
            '0000FF', // Blue
            '808080', // Gray
            'FFC0CB', // Pink
            '800080', // Purple
            'FFA500', // Orange
            '008000', // Green
            'A52A2A', // Brown
            'FFD700', // Gold
            'C0C0C0', // Silver
            'F5F5DC', // Beige
            '000080', // Navy
            '808000', // Olive
        ];
        
        return $this->faker->randomElement($fashionColors);
    }
    
    /**
     * Reset the used images tracking array.
     * Call this method at the beginning of your seeder if you want to reset.
     *
     * @return void
     */
    public static function resetUsedImages(): void
    {
        self::$usedImages = [];
    }
    
    /**
     * Get count of available unused images
     *
     * @return int
     */
    public static function getAvailableImageCount(): int
    {
        $allImages = Storage::disk('public')->files('clothes');
        $allImages = array_filter($allImages, function($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        return count(array_diff($allImages, self::$usedImages));
    }
}
