<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

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
}
