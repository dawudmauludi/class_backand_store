<?php

namespace Database\Factories;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => rtrim(fake()->sentence(3), '.'),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10_000, 5_000_000),
            'stock' => fake()->numberBetween(0, 100),
            'category_id' => CategoryProduct::factory(),
        ];
    }
}
