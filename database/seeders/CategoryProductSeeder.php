<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik'],
            ['name' => 'Pakaian & Fashion'],
            ['name' => 'Komputer & Aksesoris'],
            ['name' => 'Handphone & Tablet'],
            ['name' => 'Gaming & Hobi'],
            ['name' => 'Buku & Alat Tulis'],
            ['name' => 'Kesehatan & Kecantikan'],
            ['name' => 'Peralatan Rumah Tangga'],
        ];

        foreach ($categories as $category) {
            CategoryProduct::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
