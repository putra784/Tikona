@extends('layouts.app')

@section('title', 'Order Confirmed — Tikona Coffee')

@section('content')
    <div class="max-w-md mx-auto text-center py-8">
        <div class="w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-3xl mx-auto mb-4">
            &#10003;
        </div>
        <h1 class="text-2xl font-extrabold mb-1">Order Successfully Created</h1>
        <p class="text-stone-500 mb-8">Thanks! Your order is being prepared.</p>

        <div class="bg-white rounded-2xl border border-stone-200 p-5 text-left space-y-3 mb-8">
            <div class="flex justify-between text-sm">
                <span class="text-stone-500">Order ID</span>
                <span class="font-semibold">#{{ $transaction->id }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-stone-500">Order Type</span>
                <span class="font-semibold">{{ $transaction->order_type === 'dine_in' ? 'Dine In' : 'Takeaway' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-stone-500">Total</span>
                <span class="font-semibold">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-stone-500">Payment Status</span>
                <span class="font-semibold capitalize">{{ $transaction->payment->status ?? 'pending' }}</span>
            </div>
        </div>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('home') }}"
               class="border border-stone-200 px-5 py-3 rounded-full font-semibold hover:border-amber-800 hover:text-amber-800 transition">
                Back to Home
            </a>
        </div>
    </div>
@endsection
