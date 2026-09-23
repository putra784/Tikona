@extends('layouts.app')

@section('title', 'Review Order — Tikona Coffee')

@section('content')

    <div class="mx-auto max-w-4xl px-6 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <p class="mb-2 text-sm font-semibold uppercase tracking-widest text-[var(--brand)]">
                Tikona Coffee
            </p>

            <h1 class="text-3xl font-bold tracking-tight text-[var(--charcoal)]">
                Review Your Order
            </h1>

            <div class="mt-3 inline-flex items-center rounded-full bg-[var(--brand-soft)] px-4 py-2">
                <span class="text-sm font-semibold text-[var(--brand-dark)]">
                    {{ $orderType === 'dine_in' ? 'Dine In' : 'Takeaway' }}
                </span>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--line)] bg-white">

            {{-- Items --}}
            <div class="divide-y divide-[var(--line)]">

                @foreach ($items as $item)
                    <div class="flex items-center justify-between gap-4 p-5">

                        <div class="min-w-0">
                            <p class="font-semibold text-[var(--charcoal)]">
                                {{ $item['product']->name }}
                            </p>

                            <p class="mt-1 text-sm text-[var(--charcoal-soft)]">
                                {{ $item['quantity'] }}
                                &times;
                                Rp {{ number_format($item['product']->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <p class="whitespace-nowrap font-semibold text-[var(--charcoal)]">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </p>

                    </div>
                @endforeach

            </div>

            {{-- Total --}}
            <div class="flex items-center justify-between border-t border-[var(--line)] bg-[var(--beige)] px-5 py-5">
                <div>
                    <p class="text-sm text-[var(--charcoal-soft)]">
                        Total Payment
                    </p>

                    <p class="mt-1 text-xl font-bold text-[var(--charcoal)]">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </p>
                </div>

                <span class="rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-[var(--brand-dark)]">
                    {{ count($items) }} {{ count($items) === 1 ? 'item' : 'items' }}
                </span>
            </div>

        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

            <a href="{{ route('order.products') }}"
                class="inline-flex items-center justify-center rounded-full border border-[var(--line)] bg-white px-6 py-3 text-sm font-semibold text-[var(--charcoal)] transition-colors hover:border-[var(--brand)] hover:text-[var(--brand-dark)]">
                <span class="mr-2">&larr;</span>
                Edit Cart
            </a>

            <form action="{{ route('order.confirm') }}" method="POST">
                @csrf

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-full bg-[var(--brand)] px-7 py-3 text-sm font-semibold text-white transition-colors hover:bg-[var(--brand-dark)] sm:w-auto">
                    Confirm Order
                    <span class="ml-2">&rarr;</span>
                </button>
            </form>

        </div>

    </div>

@endsection