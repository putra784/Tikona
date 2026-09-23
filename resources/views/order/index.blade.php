@extends('layouts.app')

@section('title', 'Start Your Order — Tikona Coffee')

@section('content')
    <div class="max-w-sm mx-auto text-center py-8">
        <h1 class="text-2xl font-extrabold mb-2">How would you like to order?</h1>
        <p class="text-stone-500 mb-8">Choose an option to continue.</p>

        <form action="{{ route('order.type') }}" method="POST" class="space-y-3">
            @csrf
            <button type="submit" name="order_type" value="dine_in"
                    class="w-full bg-white border border-stone-200 rounded-2xl py-4 font-semibold hover:border-amber-800 hover:text-amber-800 transition
                           {{ $orderType === 'dine_in' ? 'border-amber-800 text-amber-800' : '' }}">
                Dine In
            </button>
            <button type="submit" name="order_type" value="takeaway"
                    class="w-full bg-white border border-stone-200 rounded-2xl py-4 font-semibold hover:border-amber-800 hover:text-amber-800 transition
                           {{ $orderType === 'takeaway' ? 'border-amber-800 text-amber-800' : '' }}">
                Takeaway
            </button>
        </form>
    </div>
@endsection
