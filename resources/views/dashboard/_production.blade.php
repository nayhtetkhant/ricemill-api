<section id="production" class="mt-5 rounded-lg border border-[#e4e9e2] bg-white">
    <div class="flex items-center justify-between gap-3 border-b border-[#edf0ec] px-5 py-4 sm:px-6">
        <div>
            <h2 class="text-sm font-semibold text-[#273129]">Production in progress</h2>
            <p class="mt-1 text-xs text-[#909990]">Batches that do not have an end date</p>
        </div>
        <span class="rounded-md bg-[#f5f7f4] px-2.5 py-1 text-[11px] font-medium text-[#687368]">{{ $openBatchCount }} open</span>
    </div>

    @if ($openBatches->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full min-w-140 text-left text-xs">
                <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Batch</th>
                        <th class="px-4 py-3 font-semibold">Raw material</th>
                        <th class="px-4 py-3 font-semibold">Started</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 text-right font-semibold">Input</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eff2ee]">
                    @foreach ($openBatches as $batch)
                        <tr class="hover:bg-[#fbfcfa]">
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-[#344137]">{{ $batch->batch_number }}</td>
                            <td class="px-4 py-4 text-[#59645b]">{{ $batch->rawProduct->name }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-[#7e887f]">{{ $batch->start_date->format('M j, Y') }}</td>
                            <td class="px-4 py-4"><span class="inline-flex rounded bg-[#eef3ed] px-2 py-1 text-[10px] font-medium text-[#55705a]">{{ str($batch->status)->headline() }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-right font-medium tabular-nums text-[#344137]">{{ number_format((float) $batch->input_quantity, 2) }} {{ $batch->rawProduct->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-10 text-center">
            <p class="text-sm font-medium text-[#536054]">No open production batches</p>
            <p class="mt-1 text-xs text-[#939c94]">Active batches will appear here.</p>
        </div>
    @endif
</section>