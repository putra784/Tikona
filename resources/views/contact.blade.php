@extends('layouts.app')

@section('title', 'Contact — Tikona')

@section('meta_description', 'Get in touch with Tikona Coffee. Find our contact information, social media, and opening hours.')

@section('content')

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden">

        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-10 lg:py-28">

            <div class="mx-auto max-w-3xl text-center">

                <span
                    class="inline-flex items-center rounded-full border border-[var(--line)] bg-[var(--brand-soft)] px-4 py-1.5 text-sm font-semibold text-[var(--brand-dark)]"
                >
                    Get in Touch
                </span>

                <h1
                    class="mt-6 text-4xl font-bold tracking-tight text-[var(--charcoal)] sm:text-5xl lg:text-6xl"
                >
                    We'd love to hear from you.
                </h1>

                <p
                    class="mx-auto mt-5 max-w-2xl text-base leading-7 text-[var(--charcoal-soft)] sm:text-lg"
                >
                    Have a question, feedback, or simply want to say hello?
                    Reach out to Tikona through any of the channels below.
                </p>

            </div>

        </div>

    </section>


    {{-- ============ CONTACT INFORMATION ============ --}}
    <section class="pb-20 lg:pb-28">

        <div class="mx-auto max-w-7xl px-6 lg:px-10">

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">


                {{-- Email --}}
                <a
                    href="mailto:hello@tikonacoffee.com"
                    class="group rounded-3xl border border-[var(--line)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--brand)] hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--brand-soft)] text-[var(--brand)]"
                    >

                        {{-- Email Icon --}}
                        <svg
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <rect
                                x="3"
                                y="5"
                                width="18"
                                height="14"
                                rx="2"
                                stroke="currentColor"
                                stroke-width="1.7"
                            />

                            <path
                                d="M3 7L12 13L21 7"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <h2 class="mt-5 text-lg font-bold text-[var(--charcoal)]">
                        Email
                    </h2>

                    <p class="mt-2 text-sm text-[var(--charcoal-soft)]">
                        hello@tikonacoffee.com
                    </p>

                    <span
                        class="mt-5 inline-flex text-sm font-semibold text-[var(--brand)] transition-colors group-hover:text-[var(--brand-dark)]"
                    >
                        Send an email →
                    </span>

                </a>


                {{-- WhatsApp --}}
                <a
                    href="https://example.com/whatsapp"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group rounded-3xl border border-[var(--line)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--brand)] hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--brand-soft)] text-[var(--brand)]"
                    >

                        {{-- WhatsApp Icon --}}
                        <svg
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M20 11.5C20 16.194 16.194 20 11.5 20C10.02 20 8.625 19.621 7.405 18.958L4 20L5.042 16.595C4.379 15.375 4 13.98 4 12.5C4 7.806 7.806 4 12.5 4C17.194 4 20 7.806 20 11.5Z"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M9 9.5C9.3 10.5 10.5 12.2 11.8 13.2C13.1 14.2 14.8 14.7 15.5 14.8"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                        </svg>

                    </div>

                    <h2 class="mt-5 text-lg font-bold text-[var(--charcoal)]">
                        WhatsApp
                    </h2>

                    <p class="mt-2 text-sm text-[var(--charcoal-soft)]">
                        Chat with us directly
                    </p>

                    <span
                        class="mt-5 inline-flex text-sm font-semibold text-[var(--brand)] transition-colors group-hover:text-[var(--brand-dark)]"
                    >
                        Start a conversation →
                    </span>

                </a>


                {{-- Instagram --}}
                <a
                    href="https://example.com/instagram"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group rounded-3xl border border-[var(--line)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--brand)] hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--brand-soft)] text-[var(--brand)]"
                    >

                        {{-- Instagram Icon --}}
                        <svg
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <rect
                                x="3"
                                y="3"
                                width="18"
                                height="18"
                                rx="5"
                                stroke="currentColor"
                                stroke-width="1.7"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="4"
                                stroke="currentColor"
                                stroke-width="1.7"
                            />

                            <circle
                                cx="17.2"
                                cy="6.8"
                                r="1"
                                fill="currentColor"
                            />

                        </svg>

                    </div>

                    <h2 class="mt-5 text-lg font-bold text-[var(--charcoal)]">
                        Instagram
                    </h2>

                    <p class="mt-2 text-sm text-[var(--charcoal-soft)]">
                        Follow our coffee journey
                    </p>

                    <span
                        class="mt-5 inline-flex text-sm font-semibold text-[var(--brand)] transition-colors group-hover:text-[var(--brand-dark)]"
                    >
                        Visit Instagram →
                    </span>

                </a>


                {{-- LinkedIn --}}
                <a
                    href="https://example.com/linkedin"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group rounded-3xl border border-[var(--line)] bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:border-[var(--brand)] hover:shadow-lg"
                >

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--brand-soft)] text-[var(--brand)]"
                    >

                        {{-- LinkedIn Icon --}}
                        <svg
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <rect
                                x="4"
                                y="4"
                                width="16"
                                height="16"
                                rx="2"
                                stroke="currentColor"
                                stroke-width="1.7"
                            />

                            <path
                                d="M8 10V16"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                            <circle
                                cx="8"
                                cy="7.5"
                                r="1"
                                fill="currentColor"
                            />

                            <path
                                d="M12 16V10M12 13C12 11.343 13.343 10 15 10C16.657 10 18 11.343 18 13V16"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                        </svg>

                    </div>

                    <h2 class="mt-5 text-lg font-bold text-[var(--charcoal)]">
                        LinkedIn
                    </h2>

                    <p class="mt-2 text-sm text-[var(--charcoal-soft)]">
                        Connect with Tikona
                    </p>

                    <span
                        class="mt-5 inline-flex text-sm font-semibold text-[var(--brand)] transition-colors group-hover:text-[var(--brand-dark)]"
                    >
                        Visit LinkedIn →
                    </span>

                </a>

            </div>

        </div>

    </section>


    {{-- ============ LOCATION & HOURS ============ --}}
    <section class="border-y border-[var(--line)] bg-[var(--beige)]/40">

        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-10 lg:py-24">

            <div class="grid gap-12 lg:grid-cols-2 lg:items-center">


                {{-- Location --}}
                <div>

                    <span
                        class="text-sm font-semibold uppercase tracking-[0.15em] text-[var(--brand-dark)]"
                    >
                        Visit Us
                    </span>

                    <h2
                        class="mt-3 text-3xl font-bold tracking-tight text-[var(--charcoal)] sm:text-4xl"
                    >
                        Come by for a cup.
                    </h2>

                    <p
                        class="mt-4 max-w-lg leading-7 text-[var(--charcoal-soft)]"
                    >
                        Whether you're staying for a while or grabbing coffee
                        on the go, we'd be happy to have you.
                    </p>


                    {{-- Address --}}
                    <div class="mt-8 flex gap-4">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[var(--brand-soft)] text-[var(--brand)]"
                        >

                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M20 10C20 15 12 21 12 21C12 21 4 15 4 10C4 5.582 7.582 3 12 3C16.418 3 20 5.582 20 10Z"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                />

                                <circle
                                    cx="12"
                                    cy="10"
                                    r="2.5"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                />

                            </svg>

                        </div>

                        <div>

                            <h3 class="font-semibold text-[var(--charcoal)]">
                                Our Location
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-[var(--charcoal-soft)]">
                                42 Roastmaster Way, Suite 100<br>
                                Artisan District
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Hours --}}
                <div
                    class="rounded-3xl border border-[var(--line)] bg-white p-8 lg:p-10"
                >

                    <div class="flex items-center justify-between">

                        <h3 class="text-xl font-bold text-[var(--charcoal)]">
                            Opening Hours
                        </h3>

                        <span
                            class="rounded-full bg-[var(--brand-soft)] px-3 py-1 text-xs font-semibold text-[var(--brand-dark)]"
                        >
                            Visit Us
                        </span>

                    </div>

                    <div class="mt-7 space-y-5">

                        <div class="flex items-center justify-between border-b border-[var(--line)] pb-4">

                            <span class="text-sm text-[var(--charcoal-soft)]">
                                Monday – Friday
                            </span>

                            <span class="text-sm font-semibold text-[var(--charcoal)]">
                                7:00 AM – 8:00 PM
                            </span>

                        </div>

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-[var(--charcoal-soft)]">
                                Saturday – Sunday
                            </span>

                            <span class="text-sm font-semibold text-[var(--charcoal)]">
                                8:00 AM – 9:00 PM
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ============ CTA ============ --}}
    <section>

        <div class="mx-auto max-w-4xl px-6 py-20 text-center lg:py-24">

            <h2
                class="text-3xl font-bold tracking-tight text-[var(--charcoal)] sm:text-4xl"
            >
                Ready for your next cup?
            </h2>

            <p
                class="mx-auto mt-4 max-w-xl leading-7 text-[var(--charcoal-soft)]"
            >
                Browse our menu and place your order for dine-in or takeaway.
            </p>

            <a
                href="/order"
                class="mt-8 inline-flex rounded-full bg-[var(--brand)] px-7 py-3.5 text-sm font-bold text-white transition-colors hover:bg-[var(--brand-dark)]"
            >
                Order Now
            </a>

        </div>

    </section>

@endsection