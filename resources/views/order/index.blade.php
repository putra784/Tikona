<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Start Your Order — Tikona Coffee')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >
</head>

<body class="min-h-screen bg-[#FAF8F4] text-stone-900">

    <main class="min-h-screen flex items-center justify-center px-5 py-8">

        <div class="w-full max-w-md">

            {{-- Back Button --}}
            <button
                type="button"
                onclick="history.back()"
                class="inline-flex items-center gap-2 mb-8 text-sm font-medium text-stone-500 hover:text-stone-900 transition"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Back
            </button>


            {{-- Header --}}
            <div class="mb-8">

                {{-- Small Brand --}}
                <div class="flex items-center gap-2 mb-6">

                    <div class="w-9 h-9 rounded-xl bg-[#E29C23] flex items-center justify-center">
                        <span class="text-white font-extrabold text-sm">
                            T
                        </span>
                    </div>

                    <span class="font-bold tracking-tight text-lg">
                        Tikona
                    </span>

                </div>


                {{-- Title --}}
                <p class="text-[#E29C23] text-sm font-bold uppercase tracking-[0.15em] mb-3">
                    Start your order
                </p>

                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    How would you like<br>
                    to enjoy your coffee?
                </h1>

                <p class="text-stone-500 mt-3 leading-relaxed">
                    Choose how you'd like to receive your order.
                </p>

            </div>


            {{-- Order Options --}}
            <form
                action="{{ route('order.type') }}"
                method="POST"
                class="space-y-4"
            >
                @csrf

                {{-- Dine In --}}
                <button
                    type="submit"
                    name="order_type"
                    value="dine_in"
                    class="group w-full bg-white border border-stone-200 rounded-3xl p-5 text-left
                           hover:border-[#E29C23] hover:shadow-lg hover:shadow-[#E29C23]/10
                           transition-all duration-200
                           {{ $orderType === 'dine_in'
                                ? 'border-[#E29C23] ring-2 ring-[#E29C23]/20'
                                : '' }}"
                >

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-4">

                            {{-- Icon --}}
                            <div
                                class="w-12 h-12 rounded-2xl bg-[#E29C23]/10
                                       flex items-center justify-center
                                       group-hover:bg-[#E29C23]
                                       transition"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-[#E29C23] group-hover:text-white transition"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 10h16M6 10v8m12-8v8M4 18h16M8 6h8"
                                    />
                                </svg>
                            </div>


                            <div>
                                <h2 class="font-bold text-lg">
                                    Dine In
                                </h2>

                                <p class="text-sm text-stone-500 mt-0.5">
                                    Enjoy your coffee at Tikona
                                </p>
                            </div>

                        </div>


                        {{-- Arrow --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-stone-300 group-hover:text-[#E29C23]
                                   group-hover:translate-x-1 transition"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>

                    </div>

                </button>


                {{-- Takeaway --}}
                <button
                    type="submit"
                    name="order_type"
                    value="takeaway"
                    class="group w-full bg-white border border-stone-200 rounded-3xl p-5 text-left
                           hover:border-[#E29C23] hover:shadow-lg hover:shadow-[#E29C23]/10
                           transition-all duration-200
                           {{ $orderType === 'takeaway'
                                ? 'border-[#E29C23] ring-2 ring-[#E29C23]/20'
                                : '' }}"
                >

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-4">

                            {{-- Icon --}}
                            <div
                                class="w-12 h-12 rounded-2xl bg-stone-100
                                       flex items-center justify-center
                                       group-hover:bg-[#E29C23]
                                       transition"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-stone-500 group-hover:text-white transition"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 8h12l-1 12H7L6 8z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 8a3 3 0 016 0"
                                    />
                                </svg>
                            </div>


                            <div>
                                <h2 class="font-bold text-lg">
                                    Takeaway
                                </h2>

                                <p class="text-sm text-stone-500 mt-0.5">
                                    Grab your coffee and go
                                </p>
                            </div>

                        </div>


                        {{-- Arrow --}}
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-stone-300 group-hover:text-[#E29C23]
                                   group-hover:translate-x-1 transition"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>

                    </div>

                </button>

            </form>


            {{-- Bottom Note --}}
            <div class="mt-8 flex items-center justify-center gap-2 text-xs text-stone-400">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.3 3.6L2.9 17a2 2 0 001.7 3h14.8a2 2 0 001.7-3L13.7 3.6a2 2 0 00-3.4 0z"
                    />
                </svg>

                You can change your order before checkout.
            </div>

        </div>

    </main>

</body>
</html>