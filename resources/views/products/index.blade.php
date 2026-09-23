@extends('layouts.app')

@section('title', 'Order — Tikona Coffee')

@section('content')

    <div class="mx-auto max-w-7xl px-6 py-10">

        {{-- Header --}}
        <div class="mb-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-widest text-[var(--brand)]">
                Tikona Coffee
            </p>

            <h1 class="text-3xl font-bold text-[var(--charcoal)]">
                Choose Your Coffee
            </h1>

            <p class="mt-2 text-[var(--charcoal-soft)]">
                Select your favorite drinks and add them to your order.
            </p>
        </div>


        {{-- Products --}}
        @if ($products->count() > 0)

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($products as $product)
                    <div
                        class="group overflow-hidden rounded-2xl border border-[var(--line)] bg-white transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">

                        {{-- Product Image --}}
                        <div class="aspect-[4/3] overflow-hidden bg-stone-100">

                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-sm text-stone-400">
                                    No Image
                                </div>
                            @endif

                        </div>


                        {{-- Product Information --}}
                        <div class="p-5">

                            <div class="mb-4">

                                <h2 class="text-lg font-bold text-[var(--charcoal)]">
                                    {{ $product->name }}
                                </h2>

                                @if ($product->description)
                                    <p class="mt-1 line-clamp-2 text-sm text-[var(--charcoal-soft)]">
                                        {{ $product->description }}
                                    </p>
                                @endif

                                <p class="mt-3 font-semibold text-[var(--brand)]">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </p>

                            </div>


                            {{-- Add To Cart --}}
                            <form method="POST" action="{{ route('order.cart.add') }}">

                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <button type="submit"
                                    class="w-full rounded-xl bg-[var(--brand)] px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-[var(--brand-dark)]">
                                    Add to Order
                                </button>

                            </form>

                        </div>

                    </div>
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


        {{-- Continue to Review --}}
        @if (session('cart') && count(session('cart')) > 0)
            <div class="mt-10 flex justify-end">

                <a href="{{ route('order.review') }}"
                    class="inline-flex items-center rounded-xl bg-[var(--charcoal)] px-6 py-3 font-semibold text-white transition-colors hover:opacity-90">
                    Continue to Review
                </a>

            </div>
        @endif

    </div>

@endsection
