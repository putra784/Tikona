<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Dashboard — Tikona</title>

    <meta name="description" content="View your Tikona orders, order status, and purchase history." />

    {{-- Urbanist --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand: #E29C23;
            --brand-dark: #C6841A;
            --brand-soft: #FBEBCE;
            --cream: #FDF8F0;
            --beige: #F3E9D7;
            --charcoal: #2B2420;
            --charcoal-soft: #6B6058;
            --line: #EAE0CD;
        }

        html,
        body {
            font-family: 'Urbanist', ui-sans-serif, system-ui, sans-serif;
            background-color: var(--cream);
            color: var(--charcoal);
        }

        .skip-link {
            position: absolute;
            left: -9999px;
            top: 0;
            z-index: 100;
            background: var(--charcoal);
            color: #fff;
            padding: .75rem 1.25rem;
            border-radius: 0 0 .5rem 0;
        }

        .skip-link:focus {
            left: 0;
        }

        :focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }
    </style>
</head>

<body>

    <a href="#main-content" class="skip-link">
        Skip to content
    </a>

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="border-b border-[var(--line)] bg-[var(--cream)]">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">

            {{-- Logo --}}
            <a href="{{ route('index') }}" class="text-2xl font-extrabold tracking-tight text-[var(--brand)]">
                Tikona
            </a>

            {{-- Right Navigation --}}
            <div class="flex items-center gap-5">

                <a href="{{ route('product') }}"
                    class="hidden text-sm font-semibold text-[var(--charcoal-soft)] transition hover:text-[var(--brand)] sm:block">
                    Order Coffee
                </a>

                <div class="hidden h-6 w-px bg-[var(--line)] sm:block"></div>

                <span class="text-sm font-semibold">
                    {{ $user->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="text-sm font-semibold text-[var(--charcoal-soft)] transition hover:text-red-600">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </nav>


    {{-- ==================== MAIN ==================== --}}
    <main id="main-content" class="mx-auto max-w-7xl px-6 py-12 lg:px-8">

        {{-- Welcome --}}
        <section class="mb-10">

            <p class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-[var(--brand)]">
                Your Dashboard
            </p>

            <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                Welcome back, {{ $user->name }}.
            </h1>

            <p class="mt-3 max-w-xl text-[var(--charcoal-soft)]">
                Keep track of your coffee orders and view your purchase history.
            </p>

        </section>


        {{-- ==================== STATISTICS ==================== --}}
        <section class="mb-12 grid grid-cols-1 gap-4 sm:grid-cols-3" aria-label="Order statistics">

            {{-- Total Orders --}}
            <div class="rounded-2xl border border-[var(--line)] bg-white p-6">

                <div class="flex items-center justify-between">

                    <p class="text-sm font-semibold text-[var(--charcoal-soft)]">
                        Total Orders
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--brand-soft)]">
                        <span class="text-lg">☕</span>
                    </div>

                </div>

                <p class="mt-4 text-3xl font-extrabold">
                    {{ $totalOrders }}
                </p>

            </div>


            {{-- Pending --}}
            <div class="rounded-2xl border border-[var(--line)] bg-white p-6">

                <div class="flex items-center justify-between">

                    <p class="text-sm font-semibold text-[var(--charcoal-soft)]">
                        Pending Orders
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100">
                        <span class="text-lg">◷</span>
                    </div>

                </div>

                <p class="mt-4 text-3xl font-extrabold">
                    {{ $pendingOrders }}
                </p>

            </div>


            {{-- Completed --}}
            <div class="rounded-2xl border border-[var(--line)] bg-white p-6">

                <div class="flex items-center justify-between">

                    <p class="text-sm font-semibold text-[var(--charcoal-soft)]">
                        Completed Orders
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100">
                        <span class="text-lg">✓</span>
                    </div>

                </div>

                <p class="mt-4 text-3xl font-extrabold">
                    {{ $completedOrders }}
                </p>

            </div>

        </section>


        {{-- ==================== ORDER HISTORY ==================== --}}
        <section>

            <div class="mb-6 flex items-end justify-between gap-4">

                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-[var(--brand)]">
                        Order History
                    </p>

                    <h2 class="mt-1 text-2xl font-extrabold">
                        My Orders
                    </h2>
                </div>

                <a href="{{ route('product') }}"
                    class="hidden rounded-full bg-[var(--brand)] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[var(--brand-dark)] sm:inline-flex">
                    Order Again
                </a>

            </div>


            {{-- ==================== ORDERS ==================== --}}
            @forelse ($transactions as $transaction)

                <article class="mb-5 overflow-hidden rounded-2xl border border-[var(--line)] bg-white">

                    {{-- Order Header --}}
                    <div
                        class="flex flex-col gap-4 border-b border-[var(--line)] p-6 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="font-extrabold">
                                Order #{{ $transaction->id }}
                            </p>

                            <p class="mt-1 text-sm text-[var(--charcoal-soft)]">
                                {{ $transaction->created_at->format('d M Y, H:i') }}
                            </p>

                        </div>


                        <div class="flex flex-wrap items-center gap-2">

                            {{-- Order Type --}}
                            <span class="rounded-full bg-[var(--beige)] px-3 py-1.5 text-xs font-bold">
                                {{ $transaction->order_type === 'dine_in' ? 'Dine In' : 'Takeaway' }}
                            </span>


                            {{-- Status --}}
                            @if ($transaction->status === 'pending')
                                <span class="rounded-full bg-yellow-100 px-3 py-1.5 text-xs font-bold text-yellow-700">
                                    Pending
                                </span>
                            @elseif ($transaction->status === 'confirmed')
                                <span class="rounded-full bg-blue-100 px-3 py-1.5 text-xs font-bold text-blue-700">
                                    Confirmed
                                </span>
                            @elseif ($transaction->status === 'completed')
                                <span class="rounded-full bg-green-100 px-3 py-1.5 text-xs font-bold text-green-700">
                                    Completed
                                </span>
                            @elseif ($transaction->status === 'cancelled')
                                <span class="rounded-full bg-red-100 px-3 py-1.5 text-xs font-bold text-red-700">
                                    Cancelled
                                </span>
                            @endif

                        </div>

                    </div>


                    {{-- ==================== PRODUCTS ==================== --}}
                    <div class="divide-y divide-[var(--line)] px-6">

                        @foreach ($transaction->details as $detail)
                            <div class="flex items-center justify-between gap-4 py-5">

                                <div class="min-w-0">

                                    <p class="truncate font-bold">
                                        {{ $detail->product->name }}
                                    </p>

                                    <p class="mt-1 text-sm text-[var(--charcoal-soft)]">
                                        {{ $detail->quantity }}
                                        ×
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </p>

                                </div>

                                <p class="shrink-0 font-bold">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </p>

                            </div>
                        @endforeach

                    </div>


                    {{-- ==================== TOTAL ==================== --}}
                    <div
                        class="flex items-center justify-between border-t border-[var(--line)] bg-[var(--cream)] px-6 py-5">

                        <span class="font-bold">
                            Total
                        </span>

                        <span class="text-lg font-extrabold text-[var(--brand-dark)]">
                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                        </span>

                    </div>

                </article>

            @empty

                {{-- ==================== EMPTY STATE ==================== --}}
                <div class="rounded-2xl border border-[var(--line)] bg-white px-6 py-16 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[var(--brand-soft)]">
                        <span class="text-2xl">☕</span>
                    </div>

                    <h3 class="mt-5 text-xl font-extrabold">
                        No orders yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm text-[var(--charcoal-soft)]">
                        You haven't placed an order yet.
                        Explore our coffee menu and find something you like.
                    </p>

                    <a href="{{ route('product') }}"
                        class="mt-6 inline-flex rounded-full bg-[var(--brand)] px-6 py-3 text-sm font-bold text-white transition hover:bg-[var(--brand-dark)]">
                        Browse Coffee
                    </a>

                </div>

            @endforelse

        </section>

    </main>


    {{-- ==================== FOOTER ==================== --}}
    <footer class="mt-12 border-t border-[var(--line)]">

        <div
            class="mx-auto flex max-w-7xl flex-col gap-3 px-6 py-8 text-sm text-[var(--charcoal-soft)] sm:flex-row sm:items-center sm:justify-between lg:px-8">

            <p>
                © {{ date('Y') }} Tikona. Crafted for the ritual.
            </p>

            <a href="{{ route('index') }}" class="font-semibold transition hover:text-[var(--brand)]">
                Back to Home
            </a>

        </div>

    </footer>

</body>

</html>
