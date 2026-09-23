@extends('layouts.app')

@section('title', 'Choose Products — Tikona Coffee')

@section('content')

    <div class="mx-auto max-w-7xl px-6 py-10">

        {{-- Header --}}
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p class="mb-2 text-sm font-semibold uppercase tracking-widest text-[var(--brand)]">
                    Tikona Coffee
                </p>

                <h1 class="text-3xl font-bold tracking-tight text-[var(--charcoal)]">
                    Choose Your Products
                </h1>

                <p class="mt-2 text-sm text-[var(--charcoal-soft)]">
                    Pick your favorite drinks and add them to your order.
                </p>

            </div>


            {{-- Review Order --}}
            <a href="{{ route('order.review') }}"
                class="inline-flex w-fit items-center gap-2 rounded-full border border-[var(--line)] bg-white px-5 py-2.5 text-sm font-semibold text-[var(--charcoal)] transition-colors hover:border-[var(--brand)] hover:text-[var(--brand)]">
                Review Order
                <span aria-hidden="true">→</span>
            </a>

        </div>


        {{-- Product Grid --}}
        @if ($products->count() > 0)

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($products as $product)
                    @php
                        $inCart = $cart[$product->id]['quantity'] ?? 0;
                    @endphp

                    <x-product-card-cart :product="$product" :quantity="$inCart" />
                @endforeach

            </div>
        @else
            {{-- Empty State --}}
            <div class="rounded-2xl border border-[var(--line)] bg-white/60 px-6 py-16 text-center">

                <h2 class="text-xl font-semibold text-[var(--charcoal)]">
                    No products available
                </h2>

                <p class="mt-2 text-sm text-[var(--charcoal-soft)]">
                    There are currently no products available for ordering.
                </p>

            </div>

        @endif

    </div>

@endsection
