<section id="inventory" class="min-w-0 rounded-lg border border-[#e4e9e2] bg-white">
    <div class="flex items-center justify-between gap-3 border-b border-[#edf0ec] px-5 py-4 sm:px-6">
        <div>
            <h2 class="text-sm font-semibold text-[#273129]">Stock at a glance</h2>
            <p class="mt-1 text-xs text-[#909990]">Lowest on-hand quantities</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="text-[11px] font-medium text-rice-700 hover:underline">All inventory <span aria-hidden="true">&rarr;</span></a>
    </div>

    @if ($stockProducts->isNotEmpty())
        <ul class="divide-y divide-[#eff2ee]">
            @foreach ($stockProducts as $product)
                <li class="flex items-center justify-between gap-3 px-5 py-4 sm:px-6">
                    <div class="min-w-0">
                        <p class="truncate text-xs font-medium text-[#344137]"><a href="{{ route('inventory.show', $product) }}" class="hover:text-rice-700">{{ $product->name }}</a></p>
                        <p class="mt-1 text-[10px] text-[#929b92]">{{ $product->code }} <span class="px-1">·</span> {{ match ($product->type->value) {
                            'RAW_MATERIAL' => 'Raw material',
                            'FINISHED' => 'Finished product',
                            'BY_PRODUCT' => 'By-product',
                        } }}</p>
                    </div>
                    <p class="shrink-0 text-right text-xs font-semibold tabular-nums text-[#344137]">{{ number_format((float) $product->current_stock, 2) }} <span class="font-normal text-[#8c968d]">{{ $product->unit }}</span></p>
                </li>
            @endforeach
        </ul>
    @else
        <div class="px-6 py-12 text-center">
            <p class="text-sm font-medium text-[#536054]">No products in the catalog</p>
            <p class="mt-1 text-xs text-[#939c94]">Your inventory will appear here.</p>
        </div>
    @endif
</section>