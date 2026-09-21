@extends('layouts.app')
@section('title', 'Tikona — Crafted for the Ritual, Ordered with Ease')
@section('content')

    @php
        $badges = [
            'Americano' => 'Everyday Classic',
            'Cafe Latte' => 'Customer Favorite',
            'Tikona Aren Coffee' => 'Signature',
            'V60' => 'Manual Brew',
            'Caramel Macchiato' => 'Barista Pick',
        ];
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="mx-auto max-w-7xl px-6 pt-4 lg:px-10 lg:pt-10">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div>
                <h1 class="mt-5 text-5xl font-extrabold leading-[1.05] tracking-tight text-[var(--charcoal)] sm:text-6xl">
                    Crafted for the Ritual.
                    <span class="block text-[var(--brand)]">Ordered with Ease.</span>
                </h1>

                <p class="mt-5 max-w-md text-[15px] leading-relaxed text-[var(--charcoal-soft)]">
                    Single-origin beans roasted in micro-batches and poured with meticulous
                    precision. Order ahead for lightning-fast takeaway, or order straight to
                    your table for a relaxed, serene dine-in experience.
                </p>

                <div class="mt-6 flex flex-wrap gap-2.5">
                    <span
                        class="rounded-full border border-[var(--line)] bg-white px-3.5 py-1.5 text-xs font-medium text-[var(--charcoal-soft)]">&#9889;
                        Quick Takeaway Pick-Up</span>
                    <span
                        class="rounded-full border border-[var(--line)] bg-white px-3.5 py-1.5 text-xs font-medium text-[var(--charcoal-soft)]">Dine
                        In Table Service</span>
                    <span
                        class="rounded-full border border-[var(--line)] bg-white px-3.5 py-1.5 text-xs font-medium text-[var(--charcoal-soft)]">100%
                        Direct-Trade Origin</span>
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="/product"
                        class="rounded-full border border-[var(--line)] bg-white px-7 py-3.5 text-sm font-semibold text-[var(--charcoal)] hover:bg-[var(--beige)] transition-colors">
                        Explore Menu
                    </a>

                    <a href="#ready-to-order"
                        class="rounded-full bg-[var(--brand)] px-7 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(226,156,35,0.35)] hover:bg-[var(--brand-dark)] transition-colors flex gap-1 items-center">
                        Order Now <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2.5" d="M4 12h16m-7-7l7 7l-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="mt-8 flex items-center gap-3">
                    <div class="flex -space-x-2">
                        @foreach (['JD', 'MR', 'SK'] as $initials)
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-[var(--cream)] bg-[var(--beige)] text-[10px] font-semibold text-[var(--charcoal)]">{{ $initials }}</span>
                        @endforeach
                    </div>
                    <div class="text-xs text-[var(--charcoal-soft)]">
                        <span class="font-semibold text-[var(--charcoal)]">&#9733;&#9733;&#9733;&#9733;&#9733;
                            {{ $averageRating }} / 5.0</span><br>
                        Beloved by 12,000+ patrons in the city
                    </div>
                </div>
            </div>

            <div class="relative">
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1200&q=80"
                    alt="Iced Amber Cold Foam Latte and a flat white on a wooden table"
                    class="h-[420px] w-full rounded-[2rem] object-cover shadow-[0_20px_50px_rgba(43,36,32,0.15)]" />
                <div class="absolute right-5 top-5 flex items-center gap-2 rounded-2xl bg-white/95 px-4 py-2.5 shadow-sm">
                    <span class="text-[var(--brand)]">&#9733;</span>
                    <div class="text-xs leading-tight">
                        <p class="font-semibold text-[var(--charcoal)]">{{ $averageRating }} Rating</p>
                        <p class="text-[var(--charcoal-soft)]">{{ $totalReviews }} verified notes</p>
                    </div>
                </div>
                <div
                    class="absolute bottom-5 left-5 right-5 flex items-center justify-between rounded-2xl bg-white/95 px-5 py-4 shadow-sm">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-[var(--brand)]">House Specialty</p>
                        <p class="text-sm font-semibold text-[var(--charcoal)]">Amber Cold Foam Latte</p>
                        <p class="text-xs text-[var(--charcoal-soft)]">Rp 45.000 &bull; Double Shot</p>
                    </div>
                    <a href="/order" aria-label="Order Amber Cold Foam Latte"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand)] text-white">+</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ READY TO ORDER ============ --}}
    <section id="ready-to-order" class="mx-auto max-w-7xl px-6 pb-20 pt-24 lg:px-10">
        <div class="rounded-[2rem] bg-[var(--charcoal)] p-8 text-white sm:p-12">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                <div>
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold text-[var(--brand)]">
                        &#9889; Direct Digital Ordering
                    </span>
                    <h2 class="mt-5 text-4xl font-extrabold tracking-tight">Ready to Order?</h2>
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/70">
                        Skip the register line entirely. Choose your experience below to
                        customize single-origins, alternative milks, and fresh pastries.
                    </p>

                    <div class="mt-8 grid grid-cols-3 gap-3">
                        <div class="rounded-xl bg-white/5 p-3.5">
                            <p class="text-sm font-semibold">Zero Wait</p>
                            <p class="mt-0.5 text-xs text-white/60">Live tracker</p>
                        </div>
                        <div class="rounded-xl bg-white/5 p-3.5">
                            <p class="text-sm font-semibold">Fresh Pull</p>
                            <p class="mt-0.5 text-xs text-white/60">Brewed on order</p>
                        </div>
                        <div class="rounded-xl bg-white/5 p-3.5">
                            <p class="text-sm font-semibold">Touchless</p>
                            <p class="mt-0.5 text-xs text-white/60">Apple &amp; Cards</p>
                        </div>
                    </div>
                </div>

                <form action="/order" method="GET" class="rounded-2xl bg-black/20 p-6">
                    <input type="hidden" name="order_type" id="order_type_input" value="dine_in" />

                    <p class="text-xs font-semibold uppercase tracking-wide text-white/60">1. Choose Order Type</p>
                    <div class="mt-3 grid grid-cols-2 gap-3" role="group" aria-label="Order type">
                        <button type="button" id="order-type-dinein" aria-pressed="true"
                            class="cursor-pointer flex items-center justify-center gap-2 rounded-xl bg-[var(--brand)] px-4 py-3 text-sm font-semibold text-white transition-colors">
                            &#127859; Dine In
                        </button>
                        <button type="button" id="order-type-takeaway" aria-pressed="false"
                            class="hover:bg-[var(--charcoal)] cursor-pointer flex items-center justify-center gap-2 rounded-xl bg-transparent px-4 py-3 text-sm font-semibold text-white/80 ring-1 ring-inset ring-white/20 transition-colors">
                            &#128230; Takeaway
                        </button>
                    </div>

                    <div id="table-number-section" class="mt-6">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wide text-white/60">2. Select Your Table
                                Number</p>
                        </div>
                        <input type="text" name="table_number" id="table_number_display" value="Table 04"
                            aria-label="Table number"
                            class="mt-3 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-[var(--brand)] focus:outline-none" />
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach (['T-02', 'T-04', 'T-08', 'Bar 1'] as $table)
                                <button type="button" data-table-option="{{ $table }}"
                                    class="rounded-full px-3.5 py-1.5 text-xs font-medium transition-colors {{ $table === 'T-04' ? 'bg-[var(--brand)] text-white' : 'bg-white/10 text-white/70 hover:bg-white/15' }}">
                                    {{ $table }}
                                </button>
                            @endforeach
                        </div>
                        <p class="mt-3 flex items-center gap-1.5 text-xs text-white/50">
                            &#8505; Our baristas will deliver your beverages and food directly to this table.
                        </p>
                    </div>

                    <div class="mt-7 flex items-center justify-between">
                        <span class="flex items-center gap-1.5 text-xs text-white/60">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span>
                            Barista bar currently open
                        </span>
                        <button type="submit"
                            class="rounded-full bg-[var(--brand)] px-6 py-3 text-sm font-semibold text-white hover:bg-[var(--brand-dark)] transition-colors">
                            Continue to Menu &rarr;
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- ============ BEST SELLERS ============ --}}
    <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-10">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-[var(--brand)]">Craft Bar Selection</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-[var(--charcoal)]">Our Best Sellers</h2>
                <p class="mt-2 max-w-md text-sm text-[var(--charcoal-soft)]">
                    Hand-picked favorites crafted with precision by our master baristas
                    using slow-roast techniques and seasonal micro-lot harvests.
                </p>
            </div>
        </div>

        @if ($bestSellers->isEmpty())
            <p
                class="mt-10 rounded-2xl border border-dashed border-[var(--line)] p-10 text-center text-sm text-[var(--charcoal-soft)]">
                Our seasonal menu is being refreshed &mdash; check back shortly.
            </p>
        @else
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($bestSellers as $product)
                    @php
                        $badge = $badges[$product->name] ?? (optional($product->category ?? null)->name ?? 'Featured');
                        $rating = $product->reviews_avg_rating ?? null;
                    @endphp
                    
                    <article
                        class="group flex flex-col overflow-hidden rounded-2xl border border-[var(--line)] bg-white shadow-[0_4px_20px_rgba(43,36,32,0.05)] transition-shadow hover:shadow-[0_10px_30px_rgba(43,36,32,0.10)]">
                        <div class="relative h-44 w-full overflow-hidden bg-[var(--beige)]">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80' }}"
                                alt="{{ $product->name }}" loading="lazy"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            <span
                                class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-[11px] font-semibold text-[var(--charcoal)] shadow-sm">
                                {{ $badge }}
                            </span>
                        </div>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center justify-between text-xs text-[var(--charcoal-soft)]">
                                <span>{{ optional($product->category ?? null)->name ?? 'Menu' }}</span>
                                @if ($rating)
                                    <span class="flex items-center gap-1 font-medium text-[var(--charcoal)]">
                                        <span class="text-[var(--brand)]">&#9733;</span> {{ number_format($rating, 1) }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="mt-2 text-base font-semibold text-[var(--charcoal)]">{{ $product->name }}</h3>
                            <p class="mt-1.5 flex-1 text-sm leading-relaxed text-[var(--charcoal-soft)]">
                                {{ \Illuminate\Support\Str::limit($product->description, 70) }}
                            </p>

                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-[var(--charcoal)]">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <a href="/order?product={{ $product->id }}"
                                    class="rounded-full bg-[var(--brand)] px-4 py-2 text-xs font-semibold text-white hover:bg-[var(--brand-dark)] transition-colors">
                                    + Order
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="mt-10 flex justify-center">
            <a href="/product"
                class="rounded-full border border-[var(--line)] bg-white px-6 py-3 text-sm font-semibold text-[var(--charcoal)] hover:bg-[var(--beige)] transition-colors">
                View Full Seasonal Menu &rarr;
            </a>
        </div>
    </section>

    {{-- ============ ABOUT / PHILOSOPHY ============ --}}
    <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-10">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1000&q=80"
                    alt="Interior of the Tikona flagship roastery and cafe"
                    class="h-[380px] w-full rounded-[2rem] object-cover shadow-[0_20px_50px_rgba(43,36,32,0.12)]" />
                <div
                    class="absolute bottom-5 left-5 right-5 flex items-center justify-between rounded-2xl bg-white/95 px-5 py-4 shadow-sm">
                    <div class="text-sm">
                        <p class="font-semibold text-[var(--charcoal)]">Flagship Roastery &amp; Cafe</p>
                        <p class="text-xs text-[var(--charcoal-soft)]">42 Roastmaster Way &bull; Open Daily 7am</p>
                    </div>
                    <a href="/about"
                        class="rounded-full bg-[var(--brand)] px-4 py-2 text-xs font-semibold text-white">Visit Us</a>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-[var(--brand)]">The Tikona Philosophy</p>
                <h2 class="mt-2 text-4xl font-extrabold leading-tight tracking-tight text-[var(--charcoal)]">
                    A sanctuary for coffee purists and mindful daily moments.
                </h2>
                <p class="mt-4 text-sm leading-relaxed text-[var(--charcoal-soft)]">
                    At Tikona, we believe ordering extraordinary coffee should feel as calm
                    and refined as savoring the first sip. We partner directly with
                    smallholder coffee farms across the equatorial belt, paying premium
                    wages to preserve terroir and heritage soil cultivation.
                </p>

                <div class="mt-7 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-xl bg-[var(--beige)] p-4">
                        <p class="text-sm font-semibold text-[var(--charcoal)]">Direct-Trade</p>
                        <p class="mt-1 text-xs leading-relaxed text-[var(--charcoal-soft)]">100% single-farm and
                            cooperative sourced with transparent payouts.</p>
                    </div>
                    <div class="rounded-xl bg-[var(--beige)] p-4">
                        <p class="text-sm font-semibold text-[var(--charcoal)]">Micro Roasted</p>
                        <p class="mt-1 text-xs leading-relaxed text-[var(--charcoal-soft)]">Precision temperature curves
                            calibrate acidity, crema density, and finish.</p>
                    </div>
                    <div class="rounded-xl bg-[var(--beige)] p-4">
                        <p class="text-sm font-semibold text-[var(--charcoal)]">Effortless Tech</p>
                        <p class="mt-1 text-xs leading-relaxed text-[var(--charcoal-soft)]">Seamless digital ordering
                            directly to your table or pickup counter.</p>
                    </div>
                </div>

                <a href="/about"
                    class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--brand)] hover:text-[var(--brand-dark)]">
                    Learn more about our sustainable sourcing and roastery &rarr;
                </a>
            </div>
        </div>
    </section>

    {{-- ============ REVIEWS ============ --}}
    <section class="bg-[var(--beige)]/40 py-20">
        <div class="mx-auto max-w-7xl px-6 text-center lg:px-10">
            <p class="text-xs font-semibold uppercase tracking-wide text-[var(--brand)]">Guest Testimonials</p>
            <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-[var(--charcoal)]">Loved by Our Customers</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-[var(--charcoal-soft)]">
                Over 12,000 discerning coffee lovers served across our locations every month.
            </p>

            <div class="mt-10 grid grid-cols-1 gap-6 text-left sm:grid-cols-3">
                @forelse ($reviews as $review)
                    @php
                        // Ambil nama user atau fallback jika user terhapus/guest
                        $userName = $review->user->name ?? 'Anonymous';

                        // Generate inisial 2 huruf (contoh: "Marcus Vance" -> "MV")
                        $initials = collect(explode(' ', $userName))
                            ->map(fn($segment) => strtoupper(substr($segment, 0, 1)))
                            ->take(2)
                            ->implode('');
                    @endphp

                    <div class="rounded-2xl bg-white p-6 shadow-[0_4px_20px_rgba(43,36,32,0.05)]">
                        {{-- pengguna --}}
                        <div class="mb-5 flex items-center gap-3">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-[var(--beige)] text-xs font-semibold text-[var(--charcoal)]">
                                {{ $initials }}
                            </span>
                            <div class="text-sm">
                                <p class="font-semibold text-[var(--charcoal)]">{{ $userName }}</p>
                                <p class="text-xs text-[var(--charcoal-soft)]">&#10003;
                                    {{ $review->product->name ?? 'Verified Buyer' }}</p>
                            </div>
                        </div>

                        <!-- Rating Bintang Dinamis -->
                        <p class="text-[var(--brand)]">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    &#9733; {{-- Bintang Penuh --}}
                                @else
                                    &#9734; {{-- Bintang Kosong --}}
                                @endif
                            @endfor
                        </p>

                        <p class="mt-3 text-sm leading-relaxed text-[var(--charcoal-soft)]">
                            &ldquo;{{ $review->description }}&rdquo;
                        </p>

                    </div>
                @empty
                    <div class="col-span-full text-center text-sm text-[var(--charcoal-soft)]">
                        Belum ada ulasan dari pelanggan.
                    </div>
                @endforelse
            </div>

            <div
                class="mt-12 flex flex-wrap items-center justify-center gap-x-10 gap-y-4 text-xs font-medium text-[var(--charcoal-soft)]">
                <span>Specialty Coffee Guild Member</span>
                <span>99.4% Patron Satisfaction</span>
                <span>Freshly Roasted Daily in Small Batches</span>
                <span>Encrypted Contactless Payments</span>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dineInBtn = document.getElementById('order-type-dinein');
            const takeawayBtn = document.getElementById('order-type-takeaway');
            const tableSection = document.getElementById('table-number-section');
            const orderTypeInput = document.getElementById('order_type_input');

            if (!dineInBtn || !takeawayBtn) return;

            // Kelas dasar yang digunakan oleh kedua tombol
            const baseClasses =
                "order-btn cursor-pointer flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold transition-colors";

            // Style saat tombol AKTIF
            const activeClasses = "bg-[var(--brand)] text-white";

            // Style saat tombol TIDAK AKTIF (termasuk efek hover)
            const inactiveClasses =
                "bg-transparent text-white/80 ring-1 ring-inset ring-white/20 hover:bg-[var(--charcoal)]";

            function setActive(type) {
                const isDineIn = type === 'dine_in';

                // Set style Dine In
                dineInBtn.className = `${baseClasses} ${isDineIn ? activeClasses : inactiveClasses}`;
                dineInBtn.setAttribute('aria-pressed', String(isDineIn));

                // Set style Takeaway
                takeawayBtn.className = `${baseClasses} ${!isDineIn ? activeClasses : inactiveClasses}`;
                takeawayBtn.setAttribute('aria-pressed', String(!isDineIn));

                // Tampilkan/sembunyikan section meja & update hidden input
                if (tableSection) tableSection.classList.toggle('hidden', !isDineIn);
                if (orderTypeInput) orderTypeInput.value = type;
            }

            // Event listener untuk tombol utama
            dineInBtn.addEventListener('click', () => setActive('dine_in'));
            takeawayBtn.addEventListener('click', () => setActive('takeaway'));

            // Inisialisasi tampilan awal sesuai nilai default
            const initialType = orderTypeInput ? (orderTypeInput.value || 'dine_in') : 'dine_in';
            setActive(initialType);

            // Event listener pilihan meja
            document.querySelectorAll('[data-table-option]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const tableDisplay = document.getElementById('table_number_display');
                    if (tableDisplay) {
                        tableDisplay.value = 'Table ' + btn.dataset.tableOption.replace('T-', '0')
                            .replace('Bar 1', 'Bar 1');
                    }

                    document.querySelectorAll('[data-table-option]').forEach((b) => {
                        b.classList.remove('bg-[var(--brand)]', 'text-white');
                        b.classList.add('bg-white/10', 'text-white/70');
                    });

                    btn.classList.remove('bg-white/10', 'text-white/70');
                    btn.classList.add('bg-[var(--brand)]', 'text-white');
                });
            });
        });
    </script>
@endpush
