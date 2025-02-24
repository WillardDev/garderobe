<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Tops' => 'Upper body clothing like shirts, t-shirts, blouses',
            'Bottoms' => 'Lower body clothing like pants, jeans, shorts, skirts',
            'Outerwear' => 'Clothing worn over other clothes for warmth or protection',
            'Footwear' => 'Shoes, boots, sandals, and other foot coverings',
            'Accessories' => 'Items that complement an outfit like hats, belts, scarves',
            'Formal Wear' => 'Clothing for formal occasions like suits, dresses',
            'Activewear' => 'Clothing designed for physical activities and exercise',
            'Sleepwear' => 'Clothing worn for sleeping like pajamas, nightgowns'
        ];

        // For random generation if needed
        $name = $this->faker->unique()->randomElement(array_keys($categories));
        
        return [
            'name' => $name,
            'description' => $categories[$name],
        ];
    }
}
