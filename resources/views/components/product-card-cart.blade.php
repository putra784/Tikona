@props(['product', 'quantity' => 0])

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

<article
    class="group flex flex-col overflow-hidden rounded-2xl border border-[var(--line)] bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_-14px_rgba(43,36,32,0.3)]">

    {{-- Product Image --}}
    <div class="aspect-[4/3] w-full overflow-hidden bg-[var(--beige)]">

        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
        @else
            <div class="flex h-full w-full items-center justify-center text-sm text-[var(--charcoal-soft)]">
                No photo yet
            </div>
        @endif

    </div>


    {{-- Product Information --}}
    <div class="flex flex-1 flex-col gap-4 p-5">

        {{-- Name & Price --}}
        <div class="flex items-start justify-between gap-3">

            <h3 class="text-lg font-semibold text-[var(--charcoal)]">
                {{ $product->name }}
            </h3>

            <span class="whitespace-nowrap text-base font-bold text-[var(--brand-dark)]">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>

        </div>


        {{-- Category --}}
        @if ($product->category)
            <span
                class="w-fit rounded-full bg-[var(--brand-soft)] px-3 py-1 text-xs font-semibold text-[var(--brand-dark)]">
                {{ $product->category->name }}
            </span>
        @endif


        {{-- Description --}}
        @if ($product->description)
            <p class="line-clamp-2 text-sm leading-relaxed text-[var(--charcoal-soft)]">
                {{ $product->description }}
            </p>
        @endif


        {{-- Cart Action --}}
        <div class="mt-auto">

            @if ($quantity > 0)
                <div class="rounded-xl bg-[var(--beige)] p-3">

                    <div class="mb-3 flex items-center justify-between">

                        <span class="text-xs font-medium text-[var(--charcoal-soft)]">
                            In your order
                        </span>

                        {{-- Remove --}}
                        <form action="{{ route('order.cart.remove', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-xs font-semibold text-red-500 transition-colors hover:text-red-700">
                                Remove
                            </button>
                        </form>

                    </div>


                    {{-- Quantity Controls --}}
                    <div class="flex items-center justify-between">

                        {{-- Decrease --}}
                        <form action="{{ route('order.cart.update', $product) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="quantity" value="{{ $quantity - 1 }}">

                            <button type="submit"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--line)] bg-white text-lg font-medium text-[var(--charcoal)] transition-all hover:border-[var(--brand)] hover:text-[var(--brand)]">
                                -
                            </button>

                        </form>


                        {{-- Quantity --}}
                        <div class="text-center">

                            <span class="block text-lg font-bold text-[var(--charcoal)]">
                                {{ $quantity }}
                            </span>

                            <span class="text-[10px] text-[var(--charcoal-soft)]">
                                quantity
                            </span>

                        </div>


                        {{-- Increase --}}
                        <form action="{{ route('order.cart.update', $product) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="quantity" value="{{ $quantity + 1 }}">

                            <button type="submit"
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-[var(--brand)] text-lg font-medium text-white transition-all hover:bg-[var(--brand-dark)]">
                                +
                            </button>

                        </form>

                    </div>

                </div>
            @else
                {{-- Add to Cart --}}
                <form action="{{ route('order.cart.add') }}" method="POST">
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-full bg-[var(--brand)] px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[var(--brand-dark)]">
                        Add to Order
                    </button>

                </form>
            @endif

        </div>

    </div>

</article>
