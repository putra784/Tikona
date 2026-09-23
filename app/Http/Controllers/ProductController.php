<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Public:
     * Daftar produk.
     */
    public function index()
    {
        $categories = Category::with(['products' => fn ($q) => $q->available()])->get();
        $products = Product::available()->with('category')->get();

        return view('products.index', compact('categories', 'products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
    
    public function page(Request $request)
    {
        $query = Product::with('category')
            ->where('is_available', true);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        $products = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('product', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
