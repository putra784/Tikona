<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Public:
     * Menampilkan seluruh kategori.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    /**
     * Public:
     * Menampilkan satu kategori beserta produknya.
     */
    public function show(Category $category): JsonResponse
    {
        $category->load('products');

        return response()->json([
            'data' => $category,
        ]);
    }

    /**
     * Admin:
     * Membuat kategori.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:categories,name',
            ],
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Kategori berhasil dibuat.',
            'data' => $category,
        ], 201);
    }

    /**
     * Admin:
     * Update kategori.
     */
    public function update(
        Request $request,
        Category $category
    ): JsonResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',

                Rule::unique(
                    'categories',
                    'name'
                )->ignore($category->id),
            ],
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $category->fresh(),
        ]);
    }

    /**
     * Admin:
     * Hapus kategori.
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->exists()) {
            return response()->json([
                'message' =>
                    'Kategori tidak dapat dihapus karena masih memiliki produk.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}