<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with products and categories.
     */
    public function index(Request $request): Response
    {
        $categories = CategoryProduct::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->with('category')
            ->latest()
            ->get();

        $totalProducts = Product::count();
        $totalCategories = CategoryProduct::count();
        $totalStock = (int) Product::sum('stock');
        $totalValue = (int) (Product::query()
            ->selectRaw('COALESCE(SUM(price * stock), 0) as total_value')
            ->value('total_value') ?? 0);

        return Inertia::render('Dashboard', [
            'categories' => $categories,
            'products' => $products,
            'stats' => [
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_stock' => $totalStock,
                'total_value' => $totalValue,
            ],
        ]);
    }
}
