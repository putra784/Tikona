<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Tikona — Crafted for the Ritual, Ordered with Ease')</title>
    <meta name="description" content="@yield('meta_description', 'Order Tikona coffee for Dine In or Takeaway. Single-origin beans, roasted in micro-batches, ordered in seconds.')" />

    {{-- Urbanist, used across the entire interface --}}
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

    @stack('styles')
</head>

<body class="antialiased">
    <a href="#main-content" class="skip-link">Skip to content</a>

    @php
        $navLink = function (string $path, string $label) {
            $isActive = $path === '/' ? request()->is('/') : request()->is(ltrim($path, '/') . '*');
            $classes = $isActive
                ? 'text-[var(--brand)] font-semibold'
                : 'text-[var(--charcoal-soft)] hover:text-[var(--charcoal)] transition-colors font-medium';
            return ['active' => $isActive, 'classes' => $classes];
        };
    @endphp

    {{-- ============ NAVBAR ============ --}}
    <header class="sticky top-0 z-50 border-b border-[var(--line)] bg-[var(--cream)]/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-10">
            <a href="/" class="flex items-center gap-2" aria-label="Tikona home">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <path d="M5 10.5H19.5V16C19.5 19.5898 16.5898 22.5 13 22.5H11.5C7.91015 22.5 5 19.5898 5 16V10.5Z"
                        stroke="#2B2420" stroke-width="1.6" />
                    <path d="M19.5 12H21.5C22.8807 12 24 13.1193 24 14.5C24 15.8807 22.8807 17 21.5 17H19.3"
                        stroke="#2B2420" stroke-width="1.6" stroke-linecap="round" />
                    <path d="M9 6C9 6 8 7.2 9 8.4C10 9.6 10 10.5 10 10.5" stroke="#E29C23" stroke-width="1.6"
                        stroke-linecap="round" />
                    <path d="M13.5 6C13.5 6 12.5 7.2 13.5 8.4C14.5 9.6 14.5 10.5 14.5 10.5" stroke="#E29C23"
                        stroke-width="1.6" stroke-linecap="round" />
                    <path d="M4 24.5H20" stroke="#2B2420" stroke-width="1.6" stroke-linecap="round" />
                </svg>
                <span class="text-lg font-bold tracking-tight text-[var(--charcoal)]">Tikona</span>
            </a>

            <nav class="hidden items-center gap-8 text-[15px] md:flex" aria-label="Primary">
                <a href="/" class="{{ $navLink('/', 'Home')['classes'] }}">
                    Home
                </a>

                <a href="/about" class="{{ $navLink('/about', 'About')['classes'] }}">
                    About
                </a>

                <a href="/product" class="{{ $navLink('/product', 'Product')['classes'] }}">
                    Product
                </a>
            </nav>

            <div class="flex items-center gap-3">

                <a href="/order"
                    class="hidden sm:inline-block rounded-full border border-[var(--line)] px-5 py-2 text-sm font-semibold text-white bg-[var(--brand)] hover:bg-[var(--brand-dark)] transition-colors">
                    Order Now
                </a>

                @auth

                    {{-- User sudah login --}}
                    <div class="relative hidden md:block">

                        <button id="profileButton" type="button"
                            class="cursor-pointer flex items-center justify-center
                   w-10 h-10 rounded-full
                   bg-[#E29C23] text-white
                   font-semibold text-lg
                   hover:opacity-90 transition">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>

                        <div id="profileDropdown"
                            class="hidden absolute right-0 mt-3 w-44
                   bg-white rounded-xl shadow-lg
                   border border-gray-100 overflow-hidden z-50">
                            <a href="/dashboard"
                                class="block px-4 py-3 text-gray-700
                       hover:bg-gray-50 transition">
                                Dashboard
                            </a>

                            <form action="/logout" method="POST">
                                @csrf

                                <button type="submit"
                                    class="w-full text-left px-4 py-3
                           text-red-500 hover:bg-red-50 transition">
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>
                @else
                    {{-- User belum login --}}
                    <a href="/login"
                        class="hidden md:inline-block rounded-full
               border border-[var(--line)]
               px-5 py-2
               text-sm font-semibold
               text-[var(--charcoal)]
               hover:bg-[var(--brand)]
               hover:text-white
               transition-colors">
                        Login
                    </a>

                @endauth

                {{-- Mobile hamburger toggle --}}
                <button id="mobileMenuButton" type="button"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-[var(--line)] text-[var(--charcoal)] md:hidden"
                    aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileMenu">
                    <svg id="mobileMenuIconOpen" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                    <svg id="mobileMenuIconClose" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        aria-hidden="true" class="hidden">
                        <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                </button>

            </div>
        </div>

        {{-- ============ MOBILE MENU PANEL ============ --}}
        <div id="mobileMenu" class="hidden md:hidden border-t border-[var(--line)] bg-[var(--cream)]">
            <nav class="flex flex-col px-6 py-4 gap-1 text-[15px]" aria-label="Mobile Primary">
                <a href="/" class="rounded-lg px-3 py-2.5 {{ $navLink('/', 'Home')['classes'] }}">Home</a>
                <a href="/about"
                    class="rounded-lg px-3 py-2.5 {{ $navLink('/about', 'About')['classes'] }}">About</a>
                <a href="/product"
                    class="rounded-lg px-3 py-2.5 {{ $navLink('/product', 'Product')['classes'] }}">Product</a>
            </nav>

            <div class="flex flex-col gap-3 px-6 pb-6">
                <a href="/order"
                    class="w-full text-center rounded-full border border-[var(--line)] px-5 py-2.5 text-sm font-semibold text-white bg-[var(--brand)] hover:bg-[var(--brand-dark)] transition-colors">
                    Order Now
                </a>

                @auth
                    <a href="/dashboard"
                        class="w-full text-center rounded-full border border-[var(--line)] px-5 py-2.5 text-sm font-semibold text-[var(--charcoal)] hover:bg-[var(--brand)] hover:text-white transition-colors">
                        Dashboard
                    </a>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-center rounded-full border border-[var(--line)] px-5 py-2.5 text-sm font-semibold text-red-500 hover:bg-red-50 transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login"
                        class="w-full text-center rounded-full border border-[var(--line)] px-5 py-2.5 text-sm font-semibold text-[var(--charcoal)] hover:bg-[var(--brand)] hover:text-white transition-colors">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main id="main-content">
        @yield('content')
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="border-t border-[var(--line)] bg-[var(--beige)]/50">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-10">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-[1.4fr_1fr_1fr_1fr]">
                <div>
                    <div class="flex items-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                            <path
                                d="M5 10.5H19.5V16C19.5 19.5898 16.5898 22.5 13 22.5H11.5C7.91015 22.5 5 19.5898 5 16V10.5Z"
                                stroke="#2B2420" stroke-width="1.6" />
                            <path d="M19.5 12H21.5C22.8807 12 24 13.1193 24 14.5C24 15.8807 22.8807 17 21.5 17H19.3"
                                stroke="#2B2420" stroke-width="1.6" stroke-linecap="round" />
                        </svg>
                        <span class="text-lg font-bold text-[var(--charcoal)]">Tikona</span>
                    </div>
                    <p class="mt-3 max-w-xs text-sm text-[var(--charcoal-soft)]">
                        Handcrafted moments, roasted to perfection. Seamless ordering for table or to-go.
                    </p>
                    <div class="mt-5 flex gap-3">
                        @foreach (['M4 4h16v16H4z', 'circle', 'wave', 'pin'] as $i => $icon)
                            <a href="#" aria-label="Social link"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--line)] text-[var(--charcoal-soft)] hover:text-[var(--brand)] hover:border-[var(--brand)] transition-colors">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor"
                                        stroke-width="1.6" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-[var(--charcoal)]">Explore</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-[var(--charcoal-soft)]">
                        <li>
                            <a href="/" class="hover:text-[var(--brand)]">
                                Home
                            </a>
                        </li>

                        <li>
                            <a href="/about" class="hover:text-[var(--brand)]">
                                About
                            </a>
                        </li>

                        <li>
                            <a href="/product" class="hover:text-[var(--brand)]">
                                Product
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-[var(--charcoal)]">Connect</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-[var(--charcoal-soft)]">
                        <li><a href="mailto:hello@tikonacoffee.com"
                                class="hover:text-[var(--brand)]">hello@tikonacoffee.com</a></li>
                        <li><a href="tel:+15553829011" class="hover:text-[var(--brand)]">+1 (555) 382-9011</a></li>
                        <li>42 Roastmaster Way, Suite 100</li>
                        <li>Artisan District</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-[var(--charcoal)]">Roastery Hours
                    </h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-[var(--charcoal-soft)]">
                        <li>Mon &ndash; Fri<br><span class="text-[var(--charcoal)]">7:00 AM &ndash; 8:00 PM</span></li>
                        <li>Sat &ndash; Sun<br><span class="text-[var(--charcoal)]">8:00 AM &ndash; 9:00 PM</span></li>
                    </ul>
                </div>
            </div>

            <div
                class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-[var(--line)] pt-6 text-xs text-[var(--charcoal-soft)] md:flex-row">
                <p>&copy; {{ now()->year }} Tikona Coffee Roasters. Handcrafted with reverence.</p>
                <div class="flex gap-5">
                    <a href="#" class="hover:text-[var(--brand)]">Privacy Policy</a>
                    <a href="#" class="hover:text-[var(--brand)]">Terms of Service</a>
                    <a href="#" class="hover:text-[var(--brand)]">Allergen Directory</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        profileButton?.addEventListener('click', function(event) {
            event.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            if (
                !profileButton?.contains(event.target) &&
                !profileDropdown?.contains(event.target)
            ) {
                profileDropdown?.classList.add('hidden');
            }
        });

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuIconOpen = document.getElementById('mobileMenuIconOpen');
        const mobileMenuIconClose = document.getElementById('mobileMenuIconClose');

        mobileMenuButton?.addEventListener('click', function(event) {
            event.stopPropagation();
            const isHidden = mobileMenu.classList.toggle('hidden');
            mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
            mobileMenuIconOpen?.classList.toggle('hidden', !isHidden);
            mobileMenuIconClose?.classList.toggle('hidden', isHidden);
        });

        document.addEventListener('click', function(event) {
            if (
                mobileMenu && !mobileMenu.classList.contains('hidden') &&
                !mobileMenu.contains(event.target) &&
                !mobileMenuButton?.contains(event.target)
            ) {
                mobileMenu.classList.add('hidden');
                mobileMenuButton?.setAttribute('aria-expanded', 'false');
                mobileMenuIconOpen?.classList.remove('hidden');
                mobileMenuIconClose?.classList.add('hidden');
            }
        });
    </script>
</body>
</body>

</html>
