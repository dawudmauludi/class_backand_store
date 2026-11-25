<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): Response
    {
        $products = Product::query()
            ->with('category')
            ->latest()
            ->get();

        $categories = CategoryProduct::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return Inertia::render('products/Index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        $categories = CategoryProduct::query()
            ->orderBy('name')
            ->get();

        return Inertia::render('products/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Produk berhasil ditambahkan.',
        ]);

        return to_route('products.index');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): Response
    {
        $categories = CategoryProduct::query()
            ->orderBy('name')
            ->get();

        return Inertia::render('products/Edit', [
            'product' => $product->load('category'),
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Produk berhasil diperbarui.',
        ]);

        return to_route('products.index');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Produk berhasil dihapus.',
        ]);

        return back();
    }
}
