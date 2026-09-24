<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Notification;
use Midtrans\Transaction as MidtransTransaction;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran.
     */
    public function show(Transaction $transaction)
    {
        // Pastikan transaksi milik user yang sedang login.
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Ambil detail transaksi dan payment.
        $transaction->load([
            'details.product',
            'payment',
        ]);

        // Pastikan payment tersedia.
        if (!$transaction->payment) {
            abort(404, 'Payment not found.');
        }

        return view('order.payment', [
            'transaction' => $transaction,
            'payment' => $transaction->payment,
            'snapToken' => null,
        ]);
    }


    /**
     * Membuat Snap Token dari Midtrans.
     */
    public function process(Transaction $transaction)
    {
        // Pastikan transaksi milik user yang sedang login.
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load([
            'details.product',
            'payment',
        ]);

        $payment = $transaction->payment;

        if (!$payment) {
            abort(404, 'Payment not found.');
        }

        // Jika sudah dibayar, jangan proses lagi.
        if ($payment->status === 'paid') {
            return redirect()
                ->route('order.success', $transaction)
                ->with('success', 'This order has already been paid.');
        }

        /*
        |--------------------------------------------------------------------------
        | Midtrans configuration
        |--------------------------------------------------------------------------
        */

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;


        /*
        |--------------------------------------------------------------------------
        | Transaction data
        |--------------------------------------------------------------------------
        */

        $params = [
            'transaction_details' => [
                'order_id' => $payment->midtrans_order_id,
                'gross_amount' => (int) $transaction->total_price,
            ],

            'item_details' => $transaction->details
                ->map(function ($detail) {
                    return [
                        'id' => (string) $detail->product_id,
                        'price' => (int) $detail->price,
                        'quantity' => (int) $detail->quantity,
                        'name' => $detail->product->name,
                    ];
                })
                ->values()
                ->toArray(),

            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | Request Snap Token
        |--------------------------------------------------------------------------
        */

        $snapToken = Snap::getSnapToken($params);


        /*
        |--------------------------------------------------------------------------
        | Return payment page
        |--------------------------------------------------------------------------
        */

        return view('order.payment', [
            'transaction' => $transaction,
            'payment' => $payment,
            'snapToken' => $snapToken,
        ]);
    }

    public function notification(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $notification = new Notification();

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        $payment = Payment::where(
            'midtrans_order_id',
            $orderId
        )->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found'
            ], 404);
        }

        DB::transaction(function () use (
            $payment,
            $transactionStatus,
            $fraudStatus
        ) {
            if (
                $transactionStatus === 'settlement' ||
                (
                    $transactionStatus === 'capture' &&
                    $fraudStatus === 'accept'
                )
            ) {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                $payment->transaction->update([
                    'status' => 'confirmed',
                ]);
            } elseif ($transactionStatus === 'pending') {
                $payment->update([
                    'status' => 'pending',
                ]);
            } elseif (
                $transactionStatus === 'deny' ||
                $transactionStatus === 'cancel' ||
                $transactionStatus === 'expire'
            ) {
                $payment->update([
                    'status' => 'failed',
                ]);

                $payment->transaction->update([
                    'status' => 'cancelled',
                ]);
            }
        });

        return response()->json([
            'message' => 'Notification processed'
        ]);
    }

    public function checkStatus(Transaction $transaction)
    {
        // Pastikan transaksi milik user yang login
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load('payment');

        if (!$transaction->payment) {
            abort(404, 'Payment not found.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $status = (object) MidtransTransaction::status(
                $transaction->payment->midtrans_order_id
            );

            $midtransStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? null;

            DB::transaction(function () use (
                $transaction,
                $midtransStatus,
                $fraudStatus
            ) {
                if (
                    $midtransStatus === 'settlement' ||
                    (
                        $midtransStatus === 'capture' &&
                        $fraudStatus === 'accept'
                    )
                ) {
                    $transaction->payment->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    $transaction->update([
                        'status' => 'confirmed',
                    ]);
                } elseif ($midtransStatus === 'pending') {
                    $transaction->payment->update([
                        'status' => 'pending',
                    ]);

                    $transaction->update([
                        'status' => 'pending',
                    ]);
                } elseif (
                    in_array($midtransStatus, [
                        'deny',
                        'cancel',
                        'expire',
                    ])
                ) {
                    $transaction->payment->update([
                        'status' => 'failed',
                    ]);

                    $transaction->update([
                        'status' => 'cancelled',
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'midtrans_status' => $midtransStatus,
                'payment_status' => $transaction->payment->fresh()->status,
                'transaction_status' => $transaction->fresh()->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check payment status.',
            ], 500);
        }
    }
}