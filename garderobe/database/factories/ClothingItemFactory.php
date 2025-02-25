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
        
        // Pick a random item name from the category's items
        $itemTypes = $categoryItems[$categoryName] ?? ['Clothing Item'];
        $itemName = $this->faker->randomElement($itemTypes);
        
        // Create the item name first
        $itemFullName = $this->faker->randomElement($brands) . ' ' . $itemName;
        
        // Format name for file matching (convert to lowercase, replace spaces with hyphens)
        $formattedName = strtolower(str_replace(' ', '-', $itemName));
        
        // Use a local image from storage/app/public/images
        // Get all image files from the directory
        $localImages = Storage::disk('public')->files('images');
        
        // Filter to only include image files
        $localImages = array_filter($localImages, function($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        // Check if we have any images
        if (empty($localImages)) {
            throw new \Exception('No images found in storage/app/public/images directory');
        }
        
        // Try to find an image that matches the item name
        $matchedImage = null;
        foreach ($localImages as $imagePath) {
            $filename = strtolower(pathinfo($imagePath, PATHINFO_FILENAME));
            if (strpos($filename, $formattedName) !== false) {
                $matchedImage = $imagePath;
                break;
            }
        }
        
        // If no matching image found, pick a random one
        $selectedImagePath = $matchedImage ?? $this->faker->randomElement($localImages);
        
        // Create a descriptive filename that includes the item name
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
            'category_id' => $category ? $category->id : Category::factory()->create()->id,
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
}
