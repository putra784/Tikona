<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Start Your Order — Tikona Coffee')</title>

    {{-- Urbanist, used across the entire interface --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

    @stack('styles')
</head>

<body class="min-h-screen bg-[#FAF8F4] text-stone-900">

    <div class="max-w-md mx-auto text-center py-8">
        <div
            class="w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-3xl mx-auto mb-4">
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
            <a href="{{ route('index') }}"
                class="border border-stone-200 px-5 py-3 rounded-full font-semibold hover:border-amber-800 hover:text-amber-800 transition">
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>