@props(['product'])

<article class="group flex flex-col overflow-hidden rounded-2xl border border-[var(--line)] bg-white/60 transition-shadow hover:shadow-[0_8px_24px_-12px_rgba(43,36,32,0.25)]">
    <div class="aspect-[4/3] w-full overflow-hidden bg-[var(--beige)]">
        @if ($product->image)
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                loading="lazy"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-sm text-[var(--charcoal-soft)]">
                No photo yet
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-3 p-5">
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-lg font-semibold text-[var(--charcoal)]">{{ $product->name }}</h3>
            <span class="whitespace-nowrap text-base font-bold text-[var(--brand-dark)]">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>
        </div>

        @if ($product->category)
            <span class="w-fit rounded-full bg-[var(--brand-soft)] px-3 py-1 text-xs font-semibold text-[var(--brand-dark)]">
                {{ $product->category->name }}
            </span>
        @endif

        @if ($product->description)
            <p class="text-sm leading-relaxed text-[var(--charcoal-soft)] line-clamp-2">
                {{ $product->description }}
            </p>
        @endif

        <a
            href="/order?product={{ $product->id }}"
            class="mt-auto inline-flex items-center justify-center rounded-full bg-[var(--brand)] px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[var(--brand-dark)]"
        >
            Order this
        </a>
    </div>
</article>
