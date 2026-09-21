<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Customer:
     * Membuat payment untuk transaksi.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transaction_id' => [
                'required',
                'integer',
                'exists:transactions,id',
            ],
        ]);

        $transaction = Transaction::findOrFail(
            $validated['transaction_id']
        );

        /*
         * Pastikan transaksi milik user.
         */
        if (
            $transaction->user_id !==
            $request->user()->id
        ) {
            return response()->json([
                'message' => 'Forbidden.',
            ], 403);
        }

        /*
         * Transaksi yang dibatalkan
         * tidak boleh dibayar.
         */
        if ($transaction->status === 'cancelled') {
            return response()->json([
                'message' =>
                    'Transaksi sudah dibatalkan.',
            ], 422);
        }

        /*
         * Satu transaksi hanya memiliki
         * satu payment.
         */
        if ($transaction->payment()->exists()) {
            return response()->json([
                'message' =>
                    'Transaksi sudah memiliki payment.',
            ], 422);
        }

        /*
         * Generate external order ID.
         */
        $midtransOrderId =
            'TIKONA-' .
            $transaction->id .
            '-' .
            Str::upper(Str::random(10));

        /*
         * Amount HARUS berasal dari database.
         */
        $payment = Payment::create([
            'transaction_id' =>
                $transaction->id,

            'midtrans_order_id' =>
                $midtransOrderId,

            'payment_method' =>
                null,

            'amount' =>
                $transaction->total_price,

            'status' =>
                'pending',

            'paid_at' =>
                null,
        ]);

        /*
         * Di sini nantinya kita panggil
         * Midtrans Snap / Core API.
         */
        return response()->json([
            'message' =>
                'Payment berhasil dibuat.',

            'data' =>
                $payment,
        ], 201);
    }

    /**
     * Customer:
     * Melihat payment miliknya.
     */
    public function show(
        Request $request,
        Payment $payment
    ): JsonResponse {
        if (
            $payment->transaction->user_id !==
            $request->user()->id
        ) {
            return response()->json([
                'message' => 'Forbidden.',
            ], 403);
        }

        return response()->json([
            'data' => $payment,
        ]);
    }

    /**
     * Midtrans:
     * Notification webhook.
     */
    public function notification(
        Request $request
    ): JsonResponse {
        /*
         * Validasi dasar payload.
         */
        $validated = $request->validate([
            'order_id' => [
                'required',
                'string',
                'max:255',
            ],

            'transaction_status' => [
                'required',
                'string',
                'max:100',
            ],

            'payment_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gross_amount' => [
                'required',
                'numeric',
            ],

            'status_code' => [
                'required',
                'string',
                'max:10',
            ],

            'signature_key' => [
                'required',
                'string',
            ],
        ]);

        /*
         * Signature Midtrans harus diverifikasi
         * menggunakan Server Key sebelum
         * mempercayai notification.
         *
         * Implementasikan melalui service khusus,
         * jangan taruh Server Key di frontend.
         */

        // $this->midtransService
        //     ->verifyNotification($validated);

        $payment = Payment::where(
            'midtrans_order_id',
            $validated['order_id']
        )->first();

        if (!$payment) {
            return response()->json([
                'message' =>
                    'Payment tidak ditemukan.',
            ], 404);
        }

        /*
         * Update status berdasarkan
         * notification dari Midtrans.
         */
        $payment->update([
            'payment_method' =>
                $validated['payment_type'] ?? null,

            'status' =>
                $validated['transaction_status'],
        ]);

        /*
         * Jika pembayaran berhasil,
         * transaksi dapat dikonfirmasi.
         */
        if (
            in_array(
                $validated['transaction_status'],
                ['settlement', 'capture'],
                true
            )
        ) {
            $payment->update([
                'paid_at' => now(),
            ]);

            $payment->transaction()->update([
                'status' => 'confirmed',
            ]);
        }

        return response()->json([
            'message' => 'Notification processed.',
        ]);
    }
}