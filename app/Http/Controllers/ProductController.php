<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Public:
     * Daftar produk.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],

            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'available' => [
                'nullable',
                'boolean',
            ],
        ]);

        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
         * Untuk customer, secara default
         * tampilkan produk yang tersedia.
         */
        if (!$request->user()) {
            $query->where('is_available', true);
        }

        if ($request->has('available')) {
            $query->where(
                'is_available',
                $request->boolean('available')
            );
        }

        $products = $query
            ->latest()
            ->paginate(12);

        return response()->json($products);
    }

    /**
     * Public:
     * Detail produk.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return response()->json([
            'data' => $product,
        ]);
    }

    /**
     * Admin:
     * Tambah produk.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_available' => [
                'sometimes',
                'boolean',
            ],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] =
                $request->file('image')
                    ->store('products', 'public');
        }

        $validated['is_available'] =
            $validated['is_available'] ?? true;

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Produk berhasil dibuat.',
            'data' => $product->load('category'),
        ], 201);
    }

    /**
     * Admin:
     * Update produk.
     */
    public function update(
        Request $request,
        Product $product
    ): JsonResponse {
        $validated = $request->validate([
            'category_id' => [
                'sometimes',
                'integer',
                'exists:categories,id',
            ],

            'name' => [
                'sometimes',
                'string',
                'min:2',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'price' => [
                'sometimes',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_available' => [
                'sometimes',
                'boolean',
            ],
        ]);

        if ($request->hasFile('image')) {

            if ($product->image) {
                Storage::disk('public')
                    ->delete($product->image);
            }

            $validated['image'] =
                $request->file('image')
                    ->store('products', 'public');
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Produk berhasil diperbarui.',
            'data' => $product->fresh()->load('category'),
        ]);
    }

    /**
     * Admin:
     * Hapus produk.
     */
    public function destroy(Product $product): JsonResponse
    {
        /*
         * Jangan hapus produk jika masih digunakan
         * oleh transaction_details.
         */
        if ($product->details()->exists()) {
            return response()->json([
                'message' =>
                    'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi.',
            ], 422);
        }

        if ($product->image) {
            Storage::disk('public')
                ->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus.',
        ]);
    }
}