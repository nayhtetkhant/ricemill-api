<section aria-label="Business summary" class="mt-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ([
        ['label' => 'Sales this month', 'value' => number_format((float) $monthlySales, 0), 'description' => 'Recorded sale value', 'color' => 'bg-[#43845b]'],
        ['label' => 'Paddy purchases', 'value' => number_format((float) $monthlyPurchases, 0), 'description' => 'Purchase value this month', 'color' => 'bg-[#c58a3b]'],
        ['label' => 'Open batches', 'value' => number_format($openBatchCount), 'description' => 'Production without an end date', 'color' => 'bg-[#d47a57]'],
        ['label' => 'Products', 'value' => number_format($productCount), 'description' => 'Across the product catalog', 'color' => 'bg-[#70849a]'],
    ] as $metric)
        <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm text-[#69746a]">{{ $metric['label'] }}</p>
                <span class="size-2 rounded-full {{ $metric['color'] }}"></span>
            </div>
            <p class="mt-4 text-[27px] font-semibold tabular-nums tracking-normal text-[#202821]">{{ $metric['value'] }}</p>
            <p class="mt-1.5 text-xs text-[#929b92]">{{ $metric['description'] }}</p>
        </article>
    @endforeach
</section>