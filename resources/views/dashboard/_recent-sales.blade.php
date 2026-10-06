<section id="recent-sales" class="min-w-0 rounded-lg border border-[#e4e9e2] bg-white">
    <div class="flex items-center justify-between gap-3 border-b border-[#edf0ec] px-5 py-4 sm:px-6">
        <div>
            <h2 class="text-sm font-semibold text-[#273129]">Recent sales</h2>
            <p class="mt-1 text-xs text-[#909990]">Latest invoices recorded</p>
        </div>
        <span class="rounded-md bg-[#f5f7f4] px-2.5 py-1 text-[11px] font-medium text-[#687368]">{{ $recentSales->count() }} shown</span>
    </div>

    @if ($recentSales->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full min-w-160 text-left text-xs">
                <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Invoice</th>
                        <th class="px-4 py-3 font-semibold">Customer</th>
                        <th class="px-4 py-3 font-semibold">Date</th>
                        <th class="px-4 py-3 font-semibold">Payment</th>
                        <th class="px-6 py-3 text-right font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eff2ee]">
                    @foreach ($recentSales as $sale)
                        <tr class="transition hover:bg-[#fbfcfa]">
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-[#344137]">{{ $sale->invoice_number }}</td>
                            <td class="px-4 py-4 text-[#59645b]">{{ $sale->customer->name }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-[#7e887f]">{{ $sale->sale_date->format('M j, Y') }}</td>
                            <td class="px-4 py-4">
                                @php($paymentStyle = match ($sale->payment_status) {
                                    'paid' => 'bg-[#edf6ef] text-[#39734e]',
                                    'partial' => 'bg-[#fff5e8] text-[#9b6726]',
                                    default => 'bg-[#f4f1ed] text-[#776d60]',
                                })
                                <span class="inline-flex rounded px-2 py-1 text-[10px] font-medium {{ $paymentStyle }}">{{ str($sale->payment_status)->headline() }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right font-semibold tabular-nums text-[#344137]">{{ number_format((float) $sale->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <p class="text-sm font-medium text-[#536054]">No sales recorded yet</p>
            <p class="mt-1 text-xs text-[#939c94]">Sales will appear here as invoices are added.</p>
        </div>
    @endif
</section>