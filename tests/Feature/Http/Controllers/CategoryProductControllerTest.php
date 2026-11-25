<?php

use App\Models\CategoryProduct;
use App\Models\User;

test('guests are redirected to login when accessing categories', function () {
    $this->get(route('categories.index'))->assertRedirect(route('login'));
    $this->post(route('categories.store'), ['name' => 'Elektronik'])->assertRedirect(route('login'));
});

test('authenticated users can view categories index', function () {
    $user = User::factory()->create();
    CategoryProduct::factory()->create(['name' => 'Elektronik']);

    $response = $this->actingAs($user)->get(route('categories.index'));

    $response->assertOk();
    $response->assertSee('Elektronik');
});

test('authenticated users can create a product category', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from(route('categories.index'))
        ->post(route('categories.store'), [
            'name' => 'Aksesoris',
        ]);

    $response->assertRedirect(route('categories.index'));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('category_product', [
        'name' => 'Aksesoris',
    ]);
});

test('category creation validates required and unique name', function () {
    $user = User::factory()->create();
    CategoryProduct::factory()->create(['name' => 'Fashion']);

    $this->actingAs($user)
        ->from(route('categories.index'))
        ->post(route('categories.store'), ['name' => ''])
        ->assertSessionHasErrors('name');

    $this->actingAs($user)
        ->from(route('categories.index'))
        ->post(route('categories.store'), ['name' => 'Fashion'])
        ->assertSessionHasErrors('name');
});

test('authenticated users can update a product category', function () {
    $user = User::factory()->create();
    $category = CategoryProduct::factory()->create(['name' => 'Lama']);

    $response = $this->actingAs($user)
        ->from(route('categories.index'))
        ->put(route('categories.update', $category), [
            'name' => 'Baru',
        ]);

    $response->assertRedirect(route('categories.index'));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('category_product', [
        'id' => $category->id,
        'name' => 'Baru',
    ]);
});

test('category update allows keeping the same name but rejects duplicates', function () {
    $user = User::factory()->create();
    $categoryA = CategoryProduct::factory()->create(['name' => 'Kategori A']);
    CategoryProduct::factory()->create(['name' => 'Kategori B']);

    $this->actingAs($user)
        ->from(route('categories.index'))
        ->put(route('categories.update', $categoryA), ['name' => 'Kategori A'])
        ->assertRedirect(route('categories.index'))
        ->assertSessionHasNoErrors();

    $this->actingAs($user)
        ->from(route('categories.index'))
        ->put(route('categories.update', $categoryA), ['name' => 'Kategori B'])
        ->assertSessionHasErrors('name');
});

test('authenticated users can delete a product category', function () {
    $user = User::factory()->create();
    $category = CategoryProduct::factory()->create(['name' => 'Hapus']);

    $response = $this->actingAs($user)
        ->from(route('categories.index'))
        ->delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseMissing('category_product', [
        'id' => $category->id,
    ]);
});
