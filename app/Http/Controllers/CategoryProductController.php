<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryProductRequest;
use App\Http\Requests\UpdateCategoryProductRequest;
use App\Models\CategoryProduct;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of product categories.
     */
    public function index(): Response
    {
        $categories = CategoryProduct::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return Inertia::render('categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product category in storage.
     */
    public function store(StoreCategoryProductRequest $request): RedirectResponse
    {
        CategoryProduct::create($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Kategori produk berhasil ditambahkan.',
        ]);

        return back();
    }

    /**
     * Update the specified product category in storage.
     */
    public function update(UpdateCategoryProductRequest $request, CategoryProduct $category): RedirectResponse
    {
        $category->update($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Kategori produk berhasil diperbarui.',
        ]);

        return back();
    }

    /**
     * Remove the specified product category from storage.
     */
    public function destroy(CategoryProduct $category): RedirectResponse
    {
        $category->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Kategori produk berhasil dihapus.',
        ]);

        return back();
    }
}
