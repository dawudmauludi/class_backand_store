<?php

use App\Models\CategoryProduct;
use App\Models\Product;
use Database\Seeders\DatabaseSeeder;

test('product can be created with uuid and belongs to category', function () {
    $category = CategoryProduct::factory()->create(['name' => 'Gadget']);

    $product = Product::create([
        'name' => 'Mechanical Keyboard',
        'description' => 'Keyboard mekanik dengan switch linear.',
        'price' => 750000,
        'stock' => 10,
        'category_id' => $category->id,
    ]);

    expect($product->id)->toBeString()
        ->and(strlen($product->id))->toBe(36)
        ->and($product->name)->toBe('Mechanical Keyboard')
        ->and($product->price)->toBe(750000)
        ->and($product->stock)->toBe(10)
        ->and($product->category->id)->toBe($category->id)
        ->and($product->categoryProduct->id)->toBe($category->id);

    $this->assertDatabaseHas('product', [
        'id' => $product->id,
        'name' => 'Mechanical Keyboard',
        'category_id' => $category->id,
    ]);
});

test('product factory creates category automatically when not provided', function () {
    $product = Product::factory()->create();

    expect($product->category)->toBeInstanceOf(CategoryProduct::class)
        ->and($product->category_id)->toBe($product->category->id);
});

test('product seeder and database seeder populate products', function () {
    $this->seed(DatabaseSeeder::class);

    expect(CategoryProduct::count())->toBeGreaterThanOrEqual(8)
        ->and(Product::count())->toBeGreaterThanOrEqual(13);

    $this->assertDatabaseHas('product', [
        'name' => 'Smart TV 43 Inch 4K UHD',
    ]);
});
