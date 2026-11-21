<?php

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\User;

test('dashboard controller renders dashboard page with products and categories', function () {
    $user = User::factory()->create();
    $category = CategoryProduct::factory()->create(['name' => 'Fashion']);
    Product::factory()->create([
        'name' => 'Kemeja Flannel',
        'category_id' => $category->id,
        'price' => 150000,
        'stock' => 5,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Kemeja Flannel');
});
