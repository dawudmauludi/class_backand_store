<?php

namespace Database\Factories;

use App\Models\CategoryProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CategoryProduct>
 */
class CategoryProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Elektronik',
                'Pakaian & Fashion',
                'Komputer & Aksesoris',
                'Handphone & Tablet',
                'Gaming & Hobi',
                'Buku & Alat Tulis',
                'Kesehatan & Kecantikan',
                'Peralatan Rumah Tangga',
                'Otomotif',
                'Olahraga',
            ]),
        ];
    }
}
