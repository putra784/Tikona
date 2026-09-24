<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * GET /order
     * Entry point. If a product id is passed via ?product=, add it to the cart
     * automatically (product-first ordering flow).
     */
    public function index(Request $request)
    {
        if ($request->filled('product')) {
            $product = Product::available()->find($request->query('product'));

            if ($product) {
                $cart = session('cart', []);

                if (!isset($cart[$product->id])) {
                    $cart[$product->id] = ['quantity' => 1];
                    session(['cart' => $cart]);
                }
            }
        }

        return view('order.index', [
            'orderType' => session('order_type'),
        ]);
    }

    /**
     * POST /order/type
     */
    public function setType(Request $request)
    {
        $validated = $request->validate([
            'order_type' => ['required', Rule::in(['dine_in', 'takeaway'])],
        ]);

        session(['order_type' => $validated['order_type']]);

        return redirect()->route('order.products');
    }

    /**
     * GET /order/products
     */
    public function products()
    {
        if (!session()->has('order_type')) {
            return redirect()->route('order.index');
        }

        $products = Product::available()->with('category')->get();
        $cart = session('cart', []);
        $orderType = session('order_type');

        return view('order.products', compact('orderType', 'products', 'cart'));
    }

    /**
     * POST /order/cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $product = Product::available()->find($validated['product_id']);

        if (!$product) {
            return back()->withErrors(['product_id' => 'Product is not available.']);
        }

        $cart = session('cart', []);
        $quantity = $validated['quantity'] ?? 1;

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = ['quantity' => $quantity];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Product added to cart.');
    }

    /**
     * PUT /order/cart/{product}
     */
    public function updateCart(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cart = session('cart', []);

        if (!isset($cart[$product->id])) {
            return back()->withErrors(['product_id' => 'Product is not in cart.']);
        }

        $cart[$product->id]['quantity'] = $validated['quantity'];
        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    /**
     * DELETE /order/cart/{product}
     */
    public function removeFromCart(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Product removed from cart.');
    }

    /**
     * GET /order/review
     */
    public function review()
    {
        if (!session()->has('order_type')) {
            return redirect()->route('order.index');
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('order.products')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $data) {
            $product = $products->get($productId);

            if (!$product) {
                continue;
            }

            $subtotal = $product->price * $data['quantity'];
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $data['quantity'],
                'subtotal' => $subtotal,
            ];
        }

        return view('order.review', [
            'orderType' => session('order_type'),
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * POST /order/confirm
     */
    public function confirm(Request $request)
    {
        // User harus login sebelum membuat transaction
        if (!Auth::check()) {
            session()->put('url.intended', route('order.review'));
            
            return redirect()
                ->route('login')
                ->with('info', 'Please login to continue your order.');
        }

        $orderType = session('order_type');
        $cart = session('cart', []);

        if (!$orderType || empty($cart)) {
            return redirect()
                ->route('order.index')
                ->withErrors([
                    'order' => 'Your order session has expired.'
                ]);
        }

        // Re-check product availability
        // dan ambil harga terbaru dari database.
        $products = Product::whereIn('id', array_keys($cart))
            ->available()
            ->get()
            ->keyBy('id');

        // Pastikan semua product masih tersedia
        foreach (array_keys($cart) as $productId) {
            if (!$products->has($productId)) {
                return redirect()
                    ->route('order.review')
                    ->withErrors([
                        'cart' => 'One or more products are no longer available.'
                    ]);
            }
        }

        $transaction = DB::transaction(function () use (
            $orderType,
            $cart,
            $products
        ) {

            $total = 0;

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'order_type' => $orderType,
                'total_price' => 0,
                'status' => 'pending',
            ]);

            // Create transaction details
            foreach ($cart as $productId => $data) {

                $product = $products->get($productId);

                $quantity = (int) $data['quantity'];

                if ($quantity < 1) {
                    throw ValidationException::withMessages([
                        'cart' => 'Invalid product quantity.'
                    ]);
                }

                // Harga authoritative dari database
                $price = $product->price;

                $subtotal = $price * $quantity;

                $total += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            // Update total setelah semua detail dihitung
            $transaction->update([
                'total_price' => $total,
            ]);

            // Buat payment record.
            // Payment gateway/session akan diproses
            // pada halaman payment.
            Payment::create([
                'transaction_id' => $transaction->id,
                'midtrans_order_id' => 'TIKONA-' . $transaction->id,
                'payment_method' => null,
                'amount' => $total,
                'status' => 'pending',
                'paid_at' => null,
            ]);

            return $transaction;
        });

        // Cart tidak diperlukan lagi setelah transaction dibuat.
        session()->forget([
            'order_type',
            'cart',
            'pending_order_confirmation',
        ]);

        // Redirect ke halaman PAYMENT, bukan success.
        return redirect()
            ->route('order.payment', $transaction);
    }

    /**
     * GET /order/success/{transaction}
     */
    public function success(Transaction $transaction)
    {
        if (Auth::check() && $transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['details.product', 'payment']);

        return view('order.success', compact('transaction'));
    }
}
