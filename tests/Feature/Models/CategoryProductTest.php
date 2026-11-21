<?php

use App\Models\CategoryProduct;
use App\Models\Product;
use Database\Seeders\CategoryProductSeeder;

test('category product can be created with uuid', function () {
    $category = CategoryProduct::create([
        'name' => 'Elektronik',
    ]);

    expect($category->id)->toBeString()
        ->and(strlen($category->id))->toBe(36)
        ->and($category->name)->toBe('Elektronik');

    $this->assertDatabaseHas('category_product', [
        'id' => $category->id,
        'name' => 'Elektronik',
    ]);
});

test('category product has many products relationship', function () {
    $category = CategoryProduct::factory()->create();

    $products = Product::factory()->count(3)->create([
        'category_id' => $category->id,
    ]);

    expect($category->products)->toHaveCount(3)
        ->and($category->products->first()->category_id)->toBe($category->id);
});

test('category product seeder populates categories', function () {
    $this->seed(CategoryProductSeeder::class);

    $this->assertDatabaseHas('category_product', [
        'name' => 'Elektronik',
    ]);

    expect(CategoryProduct::count())->toBeGreaterThanOrEqual(8);
});
