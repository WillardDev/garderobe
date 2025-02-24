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
        
        // Generate category-appropriate image
        $imageId = $this->faker->numberBetween(1, 1000);
        $width = 600;
        $height = 800;
        
        // For more specific category-based image URLs
        $categoryKeywords = [
            'Tops' => 'shirt,tshirt,blouse,sweater,hoodie',
            'Bottoms' => 'pants,jeans,shorts,skirt,trousers',
            'Outerwear' => 'jacket,coat,blazer,parka',
            'Footwear' => 'shoes,sneakers,boots,sandals',
            'Accessories' => 'hat,scarf,bag,watch,sunglasses',
            'Formal Wear' => 'suit,dress,formal,tuxedo,gown',
            'Activewear' => 'sportswear,athletic,gym,running',
            'Sleepwear' => 'pajamas,nightwear,robe'
        ];
        
        $keyword = strtolower(str_replace(' ', '-', $itemName));
        $categoryKeyword = $categoryKeywords[$categoryName] ?? 'clothing';
        
        // Choose from different image providers
        $imageProviders = [
            "https://picsum.photos/id/{$this->faker->numberBetween(1, 70)}/{$width}/{$height}",
            // "https://picsum.photos/id/{$this->faker->numberBetween(1, 70)}/{$this->faker->word}/{$width}/{$height}",
            // "https://picsum.photos/id/{$this->faker->numberBetween(1, 70)}/{$width}/{$height}?grayscale",
            // "https://picsum.photos/id/{$this->faker->numberBetween(1, 70)}/{$width}/{$height}/?blur",
        ];
        
        // Generate the image path and URL
        $imagePath = 'images/' . $this->faker->uuid;
        $imageUrl = $this->faker->randomElement($imageProviders);
        
        // Download the image contents from the URL
        $imageContents = file_get_contents($imageUrl);
        
        // Get the content type of the image
        $contentType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $imageContents);
                
        // Check if the content type is in the required formats
        $allowedFormats = ['image/jpeg', 'image/png'];
        
        $fileExtension = match ($contentType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            default => 'jpg',
        };
        
        // Update the image path with the correct file extension
        $imagePath .= '.' . $fileExtension;
        
        // Store the image in the local storage disk
        Storage::disk('images')->put($imagePath, $imageContents);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'category_id' => $category ? $category->id : Category::factory()->create()->id,
            'name' => $this->faker->randomElement($brands) . ' ' . $itemName,
            'description' => $this->faker->sentence(6, true),
            'color' => $this->faker->randomElement($colors),
            'size' => $this->faker->randomElement($sizes),
            'brand' => $this->faker->randomElement($brands),
            'image_path' => $imagePath,
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
