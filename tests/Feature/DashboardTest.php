<?php

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard and see products and categories', function () {
    $user = User::factory()->create();
    $category = CategoryProduct::factory()->create(['name' => 'Elektronik']);
    Product::factory()->count(2)->create(['category_id' => $category->id]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Dashboard')
        ->has('categories', 1)
        ->has('products', 2)
        ->has('stats.total_products')
        ->has('stats.total_categories')
        ->has('stats.total_stock')
        ->has('stats.total_value')
    );
});
