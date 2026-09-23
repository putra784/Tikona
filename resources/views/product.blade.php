@extends('layouts.app') @section('content') <div class="mx-auto max-w-7xl px-6 py-10"> {{-- Header --}} <div
            class="mb-8">
            <h1 class="text-3xl font-bold text-[var(--charcoal)]"> Our Products </h1>
            <p class="mt-2 text-[var(--charcoal-soft)]"> Discover your favorite coffee from Tikona. </p>
        </div> {{-- Search & Filter --}} <form method="GET" action="{{ route('product') }}" class="mb-10">
            <div class="flex flex-col gap-4 md:flex-row"> {{-- Search --}} <div class="flex-1"> <input type="text"
                        name="search" value="{{ request('search') }}" placeholder="Search coffee..."
                        class="w-full rounded-xl border border-[var(--line)] bg-white px-4 py-3 text-[var(--charcoal)] outline-none transition focus:border-[var(--brand)]">
                </div> {{-- Category Filter --}} <div class="md:w-56"> <select name="category_id"
                        class="w-full rounded-xl border border-[var(--line)] bg-white px-4 py-3 text-[var(--charcoal)] outline-none transition focus:border-[var(--brand)]">
                        <option value=""> All Categories </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)> {{ $category->name }}
                            </option>
                            @endforeach
                    </select> </div> {{-- Search Button --}} <button type="submit"
                    class="rounded-xl bg-[var(--brand)] px-7 py-3 font-semibold text-white transition-colors hover:bg-[var(--brand-dark)]">
                    Search </button> </div>
        </form> {{-- Products --}} @if ($products->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            {{-- Empty State --}} <div class="rounded-2xl border border-[var(--line)] bg-white/60 px-6 py-16 text-center">
                <h2 class="text-xl font-semibold text-[var(--charcoal)]"> No products found </h2>
                <p class="mt-2 text-sm text-[var(--charcoal-soft)]"> Try another product name or category. </p>
                @if (request('search') || request('category_id'))
                    <a href="{{ route('product') }}"
                        class="mt-5 inline-flex rounded-full bg-[var(--brand)] px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[var(--brand-dark)]">
                        Clear Filter </a>
                @endif
            </div>
            @endif {{-- Pagination --}} @if ($products->hasPages())
                <div class="mt-10"> {{ $products->links() }} </div>
            @endif
</div> @endsection
