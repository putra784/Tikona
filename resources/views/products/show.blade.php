@extends('layouts.app')

@section('title', $product->name . ' — Tikona Coffee')

@section('content')
    <a href="{{ route('products.index') }}" class="text-sm text-stone-500 hover:text-amber-800">&larr; Back to menu</a>

    <div class="mt-4 grid sm:grid-cols-2 gap-8">
        <div class="aspect-square bg-stone-100 rounded-2xl overflow-hidden">
            @if ($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @endif
        </div>

        <div>
            <p class="text-xs uppercase tracking-wide text-stone-400 mb-1">{{ $product->category->name }}</p>
            <h1 class="text-2xl font-extrabold mb-2">{{ $product->name }}</h1>
            <p class="text-xl font-semibold text-amber-800 mb-4">
                Rp{{ number_format($product->price, 0, ',', '.') }}
            </p>
            <p class="text-stone-600 mb-8">{{ $product->description }}</p>

            @if ($product->is_available)
                <a href="{{ route('order.index', ['product' => $product->id]) }}"
                   class="inline-block bg-amber-800 text-white px-6 py-3 rounded-full font-semibold hover:bg-amber-900 transition">
                    Order Now
                </a>
            @else
                <span class="inline-block bg-stone-200 text-stone-500 px-6 py-3 rounded-full font-semibold">
                    Currently Unavailable
                </span>
            @endif
        </div>
    </div>
@endsection
