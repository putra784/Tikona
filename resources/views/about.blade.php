@extends('layouts.app')

@section('title', 'About — Tikona')
@section('meta_description', 'Tikona roasts single-origin coffee in micro-batches and gets it to your table or hands in seconds. See how we source, roast, and brew.')

@section('content')
    <section class="mx-auto max-w-4xl px-6 pt-16 pb-14 text-center lg:px-10">
        <p class="text-sm font-semibold text-[var(--brand-dark)]">Our Story</p>
        <h1 class="mt-2 text-4xl font-bold tracking-tight text-[var(--charcoal)] sm:text-5xl">
            We started Tikona because the best coffee shouldn't come with a wait.
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-lg text-[var(--charcoal-soft)]">
            Tikona began as a single roaster with a small batch machine and a stubborn belief:
            careful sourcing and fast ordering aren't opposites. Every cup we serve is roasted
            days, not months, before it reaches you.
        </p>
    </section>

    <section class="border-y border-[var(--line)] bg-[var(--beige)]/40">
        <div class="mx-auto max-w-6xl px-6 py-16 lg:px-10">
            <h2 class="text-2xl font-bold text-[var(--charcoal)] sm:text-3xl">From bean to cup</h2>
            <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['step' => '1', 'title' => 'Source', 'text' => "We buy direct from small farms we've visited, at prices set above commodity rate."],
                    ['step' => '2', 'title' => 'Roast', 'text' => 'Beans are roasted in batches of under 15kg, tasted, and adjusted by hand.'],
                    ['step' => '3', 'title' => 'Brew', 'text' => 'Baristas dial in each origin fresh, so extraction matches the roast date.'],
                    ['step' => '4', 'title' => 'Serve', 'text' => 'Order ahead for Dine In or Takeaway — your cup is ready when you are.'],
                ] as $item)
                    <div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[var(--brand)] text-sm font-bold text-white">
                            {{ $item['step'] }}
                        </span>
                        <h3 class="mt-4 font-semibold text-[var(--charcoal)]">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-[var(--charcoal-soft)]">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-6 py-16 lg:px-10">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
            <div>
                <h3 class="text-lg font-semibold text-[var(--charcoal)]">Quality over volume</h3>
                <p class="mt-2 text-sm leading-relaxed text-[var(--charcoal-soft)]">
                    We'd rather sell out of a great batch than stretch it thin. Availability
                    changes with what's actually good that week.
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-[var(--charcoal)]">Ordering that keeps up</h3>
                <p class="mt-2 text-sm leading-relaxed text-[var(--charcoal-soft)]">
                    Browse the menu, choose Dine In or Takeaway, and pay in the app — no
                    standing in line to place an order.
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-[var(--charcoal)]">Built around the counter</h3>
                <p class="mt-2 text-sm leading-relaxed text-[var(--charcoal-soft)]">
                    Every detail, from bag to cup, is decided by the people who pull the
                    shots — not a corporate menu board.
                </p>
            </div>
        </div>
    </section>

    <section class="border-t border-[var(--line)] bg-[var(--brand-soft)]/50">
        <div class="mx-auto flex max-w-4xl flex-col items-center gap-5 px-6 py-16 text-center lg:px-10">
            <h2 class="text-2xl font-bold text-[var(--charcoal)] sm:text-3xl">Ready for a cup?</h2>
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center justify-center rounded-full bg-[var(--brand)] px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-[var(--brand-dark)]"
            >
                See the menu
            </a>
        </div>
    </section>
@endsection
