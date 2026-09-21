<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Public:
     * Melihat review sebuah produk.
     */
    public function index(Product $product): JsonResponse
    {
        $reviews = $product
            ->reviews()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Customer:
     * Membuat review.
     */
    public function store(
        Request $request,
        Product $product
    ): JsonResponse {
        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        /*
         * Satu user tidak boleh membuat
         * review berulang untuk produk yang sama.
         */
        $alreadyReviewed = Review::where(
            'user_id',
            $request->user()->id
        )
            ->where(
                'product_id',
                $product->id
            )
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' =>
                    'Anda sudah memberikan review untuk produk ini.',
            ], 422);
        }

        /*
         * User ID berasal dari authentication,
         * bukan dari request.
         */
        $review = Review::create([
            'user_id' =>
                $request->user()->id,

            'product_id' =>
                $product->id,

            'rating' =>
                $validated['rating'],

            /*
             * Review diperlakukan sebagai plain text.
             */
            'description' =>
                isset($validated['description'])
                    ? strip_tags(
                        $validated['description']
                    )
                    : null,
        ]);

        return response()->json([
            'message' =>
                'Review berhasil ditambahkan.',

            'data' =>
                $review->load('user:id,name'),
        ], 201);
    }

    /**
     * Customer:
     * Update review miliknya.
     */
    public function update(
        Request $request,
        Review $review
    ): JsonResponse {
        if (
            $review->user_id !==
            $request->user()->id
        ) {
            return response()->json([
                'message' => 'Forbidden.',
            ], 403);
        }

        $validated = $request->validate([
            'rating' => [
                'sometimes',
                'integer',
                'between:1,5',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        if (
            array_key_exists(
                'description',
                $validated
            )
        ) {
            $validated['description'] =
                $validated['description'] !== null
                    ? strip_tags(
                        $validated['description']
                    )
                    : null;
        }

        $review->update($validated);

        return response()->json([
            'message' =>
                'Review berhasil diperbarui.',

            'data' =>
                $review->fresh(),
        ]);
    }

    /**
     * Customer:
     * Hapus review miliknya.
     */
    public function destroy(
        Request $request,
        Review $review
    ): JsonResponse {
        if (
            $review->user_id !==
            $request->user()->id
        ) {
            return response()->json([
                'message' => 'Forbidden.',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' =>
                'Review berhasil dihapus.',
        ]);
    }
}