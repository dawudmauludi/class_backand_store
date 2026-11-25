<?php

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\User;

test('guests are redirected to login when accessing products', function () {
    $this->get(route('products.index'))->assertRedirect(route('login'));
    $this->get(route('products.create'))->assertRedirect(route('login'));
    $this->post(route('products.store'), [])->assertRedirect(route('login'));
});

test('authenticated users can view products index and create page', function () {
    $user = User::factory()->create();
    $category = CategoryProduct::factory()->create(['name' => 'Elektronik']);
    Product::factory()->create([
        'name' => 'Laptop Gaming',
        'category_id' => $category->id,
        'price' => 15000000,
        'stock' => 3,
    ]);

    $this->actingAs($user)
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee('Laptop Gaming');

    $this->actingAs($user)
        ->get(route('products.create'))
        ->assertOk()
        ->assertSee('Elektronik');
});

test('authenticated users can create a product', function () {
    $user = User::factory()->create();
    $category = CategoryProduct::factory()->create();

    $response = $this->actingAs($user)->post(route('products.store'), [
        'name' => 'Keyboard Mechanical',
        'description' => 'Switch biru RGB',
        'price' => 750000,
        'stock' => 12,
        'category_id' => $category->id,
    ]);

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('product', [
        'name' => 'Keyboard Mechanical',
        'description' => 'Switch biru RGB',
        'price' => 750000,
        'stock' => 12,
        'category_id' => $category->id,
    ]);
});

test('product creation validates required fields and constraints', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('products.store'), [
            'name' => '',
            'price' => -100,
            'stock' => -5,
            'category_id' => 'not-a-uuid',
        ])
        ->assertSessionHasErrors(['name', 'price', 'stock', 'category_id']);
});

test('authenticated users can view edit page and update a product', function () {
    $user = User::factory()->create();
    $categoryA = CategoryProduct::factory()->create(['name' => 'Kategori A']);
    $categoryB = CategoryProduct::factory()->create(['name' => 'Kategori B']);
    $product = Product::factory()->create([
        'name' => 'Mouse Lama',
        'description' => 'Deskripsi lama',
        'price' => 100000,
        'stock' => 5,
        'category_id' => $categoryA->id,
    ]);

    $this->actingAs($user)
        ->get(route('products.edit', $product))
        ->assertOk()
        ->assertSee('Mouse Lama');

    $response = $this->actingAs($user)->put(route('products.update', $product), [
        'name' => 'Mouse Wireless Baru',
        'description' => 'Deskripsi baru',
        'price' => 250000,
        'stock' => 20,
        'category_id' => $categoryB->id,
    ]);

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('product', [
        'id' => $product->id,
        'name' => 'Mouse Wireless Baru',
        'description' => 'Deskripsi baru',
        'price' => 250000,
        'stock' => 20,
        'category_id' => $categoryB->id,
    ]);
});

test('authenticated users can delete a product', function () {
    $user = User::factory()->create();
    $category = CategoryProduct::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $response = $this->actingAs($user)
        ->from(route('products.index'))
        ->delete(route('products.destroy', $product));

    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseMissing('product', [
        'id' => $product->id,
    ]);
});
