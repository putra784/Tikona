<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Customer:
     * Melihat transaksi miliknya.
     */
    public function index(Request $request): JsonResponse
    {
        $transactions = Transaction::with([
            'details.product',
            'payment',
        ])
            ->where(
                'user_id',
                $request->user()->id
            )
            ->latest()
            ->paginate(10);

        return response()->json($transactions);
    }

    /**
     * Customer:
     * Melihat detail transaksi miliknya.
     */
    public function show(
        Request $request,
        Transaction $transaction
    ): JsonResponse {
        if (
            $transaction->user_id !==
            $request->user()->id
        ) {
            return response()->json([
                'message' => 'Forbidden.',
            ], 403);
        }

        $transaction->load([
            'user',
            'details.product',
            'payment',
        ]);

        return response()->json([
            'data' => $transaction,
        ]);
    }

    /**
     * Customer:
     * Membuat transaksi.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_type' => [
                'required',
                'in:dine_in,takeaway',
            ],

            'details' => [
                'required',
                'array',
                'min:1',
                'max:20',
            ],

            'details.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],

            'details.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:50',
            ],
        ]);

        $transaction = DB::transaction(
            function () use ($validated, $request) {

                $totalPrice = 0;

                $transaction = Transaction::create([
                    'user_id' =>
                        $request->user()->id,

                    'order_type' =>
                        $validated['order_type'],

                    'total_price' => 0,

                    'status' => 'pending',
                ]);

                foreach (
                    $validated['details']
                    as $detail
                ) {

                    /*
                     * Ambil produk dari DATABASE.
                     *
                     * Jangan percaya price dari frontend.
                     */
                    $product = Product::findOrFail(
                        $detail['product_id']
                    );

                    /*
                     * Produk yang tidak tersedia
                     * tidak boleh dipesan.
                     */
                    if (!$product->is_available) {
                        abort(
                            422,
                            "Produk {$product->name} sedang tidak tersedia."
                        );
                    }

                    /*
                     * Gunakan harga saat transaksi.
                     */
                    $price = $product->price;

                    $quantity = $detail['quantity'];

                    $subtotal =
                        $price * $quantity;

                    $transaction
                        ->details()
                        ->create([
                            'product_id' =>
                                $product->id,

                            'quantity' =>
                                $quantity,

                            'price' =>
                                $price,

                            'subtotal' =>
                                $subtotal,
                        ]);

                    $totalPrice += $subtotal;
                }

                /*
                 * Total dihitung backend.
                 */
                $transaction->update([
                    'total_price' => $totalPrice,
                ]);

                return $transaction;
            }
        );

        $transaction->load([
            'details.product',
        ]);

        return response()->json([
            'message' =>
                'Transaksi berhasil dibuat.',

            'data' =>
                $transaction,
        ], 201);
    }

    /**
     * Admin:
     * Mengubah status transaksi.
     */
    public function updateStatus(
        Request $request,
        Transaction $transaction
    ): JsonResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,confirmed,completed,cancelled',
            ],
        ]);

        $transaction->update([
            'status' =>
                $validated['status'],
        ]);

        return response()->json([
            'message' =>
                'Status transaksi berhasil diperbarui.',

            'data' =>
                $transaction->fresh(),
        ]);
    }
}