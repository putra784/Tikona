<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Choose Products — Tikona Coffee</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Urbanist', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#FAF8F4]">

    <main class="mx-auto max-w-7xl px-6 py-10 pb-28">

        {{-- Back Button --}}
        <button type="button" onclick="history.back()"
            class="mb-8 inline-flex items-center gap-2 text-sm font-medium text-[var(--charcoal-soft)] transition-colors hover:text-[var(--charcoal)]">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
            </svg>

            Back
        </button>

        {{-- Page Introduction --}}
        <div class="mb-10">
            <div class="mb-3 inline-flex rounded-full bg-[var(--brand-soft)] px-3 py-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-[var(--brand-dark)]">
                    {{ $orderType === 'dine_in' ? 'Dine In' : 'Takeaway' }}
                </span>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-[var(--charcoal)] sm:text-4xl">
                Choose your favorites.
            </h1>

            <p class="mt-3 max-w-xl text-sm leading-relaxed text-[var(--charcoal-soft)]">
                Pick the coffee you want and add it to your order.
                You can adjust the quantity before reviewing your order.
            </p>
        </div>


        {{-- Product List --}}
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
            <div class="flex min-h-[350px] items-center justify-center">

                <div class="text-center">

                    <div
                        class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[var(--brand-soft)]">

                        <svg class="h-7 w-7 text-[var(--brand-dark)]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h12M9 21h.01M18 21h.01" />

                        </svg>

                    </div>

                    <h2 class="text-xl font-semibold text-[var(--charcoal)]">
                        No products available
                    </h2>

                    <p class="mt-2 text-sm text-[var(--charcoal-soft)]">
                        There are currently no products available.
                    </p>

                </div>

            </div>

        @endif

    </main>


    {{-- Review Order --}}
    <div class="fixed inset-x-0 bottom-0 z-40 px-6 py-4">

        <div class="mx-auto max-w-7xl">

            <a href="{{ route('order.review') }}"
                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[var(--brand)] px-6 py-4 text-sm font-semibold text-white shadow-lg transition-all hover:bg-[var(--brand-dark)] hover:shadow-xl">

                Review Order

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M9 5l7 7-7 7" />

                </svg>

            </a>

        </div>

    </div>

</body>

</html>