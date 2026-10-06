<section class="mt-5 rounded-lg border border-[#e4e9e2] bg-white">
    <div class="flex items-center justify-between gap-3 border-b border-[#edf0ec] px-5 py-4 sm:px-6">
        <div>
            <h2 class="text-sm font-semibold text-[#273129]">Recent paddy purchases</h2>
            <p class="mt-1 text-xs text-[#909990]">Latest supplier receipts and quantities received</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('purchases.index') }}" class="text-[11px] font-medium text-rice-700 hover:underline">All purchases</a>
            <a href="{{ route('purchases.create') }}" class="inline-flex size-8 items-center justify-center rounded-md bg-rice-700 text-lg leading-none text-white hover:bg-rice-600" aria-label="Record paddy purchase" title="Record purchase">+</a>
        </div>
    </div>

    @if ($recentPurchases->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full min-w-160 text-left text-xs">
                <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                    <tr>
                        <th class="px-6 py-3">Receipt</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Paddy product</th>
                        <th class="px-4 py-3 text-right">Received</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eff2ee]">
                    @foreach ($recentPurchases as $purchase)
                        <tr class="hover:bg-[#fbfcfa]">
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-[#344137]"><a href="{{ route('purchases.show', $purchase) }}" class="hover:text-rice-700">{{ $purchase->purchase_number }}</a></td>
                            <td class="px-4 py-4 text-[#59645b]">{{ $purchase->supplier->name }}</td>
                            <td class="px-4 py-4 text-[#59645b]">{{ $purchase->product->name }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-right tabular-nums text-[#59645b]">{{ number_format((float) $purchase->quantity, 2) }} {{ $purchase->product->unit }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right font-semibold tabular-nums text-[#344137]">{{ number_format((float) $purchase->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-10 text-center">
            <p class="text-sm font-medium text-[#536054]">No paddy purchases recorded yet</p>
            <p class="mt-1 text-xs text-[#939c94]">Add a supplier receipt to update raw-material inventory.</p>
            <a href="{{ route('purchases.create') }}" class="mt-4 inline-flex min-h-9 items-center justify-center rounded-md bg-rice-700 px-3 text-xs font-medium text-white hover:bg-rice-600">Record purchase</a>
        </div>
    @endif
</section>