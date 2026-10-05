<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f4">
    <title>Operations overview | {{ config('app.name', 'Rice Mill') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f7f4] font-sans text-[#202821] antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="hidden w-64 shrink-0 flex-col border-r border-[#e3e8e1] bg-white lg:sticky lg:top-0 lg:flex lg:h-screen">
            <a href="#overview" class="flex items-center gap-3 border-b border-[#edf0ec] px-6 py-6">
                <span class="flex size-10 items-center justify-center rounded-lg bg-rice-700 text-sm font-bold text-white">RM</span>
                <span>
                    <span class="block text-sm font-semibold tracking-[0.01em]">Rice Mill</span>
                    <span class="mt-0.5 block text-xs text-[#7d877e]">Operations</span>
                </span>
            </a>

            <div class="px-4 pt-7">
                <p class="px-3 text-[10px] font-semibold uppercase tracking-normal text-[#a0a9a0]">Workspace</p>
                <nav aria-label="Main navigation" class="mt-3 flex flex-col gap-1">
                    <a href="#overview" class="flex items-center gap-3 rounded-md bg-rice-50 px-3 py-2.5 text-sm font-semibold text-rice-700">
                        <span class="size-1.5 rounded-full bg-rice-600"></span>Overview
                    </a>
                    <a href="#inventory" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-[#59645b] transition hover:bg-[#f6f8f5] hover:text-[#202821]">
                        <span class="size-1.5 rounded-full border border-[#aab3aa]"></span>Inventory
                    </a>
                    <a href="#recent-sales" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-[#59645b] transition hover:bg-[#f6f8f5] hover:text-[#202821]">
                        <span class="size-1.5 rounded-full border border-[#aab3aa]"></span>Sales
                    </a>
                    <a href="#production" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-[#59645b] transition hover:bg-[#f6f8f5] hover:text-[#202821]">
                        <span class="size-1.5 rounded-full border border-[#aab3aa]"></span>Production
                    </a>
                </nav>
            </div>

            <div class="mt-auto border-t border-[#edf0ec] px-6 py-5">
                <p class="text-xs font-medium text-[#59645b]">Mill workspace</p>
                <p class="mt-1 text-[11px] text-[#9aa39a]">Stock, purchasing & sales</p>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="border-b border-[#e3e8e1] bg-white px-5 py-4 sm:px-8 lg:px-10">
                <div class="flex items-center justify-between gap-4">
                    <a href="#overview" class="flex items-center gap-2.5 lg:hidden">
                        <span class="flex size-8 items-center justify-center rounded-md bg-rice-700 text-xs font-bold text-white">RM</span>
                        <span class="text-sm font-semibold">Rice Mill</span>
                    </a>
                    <p class="hidden text-xs text-[#879187] sm:block">Operations <span class="px-1.5 text-[#c0c7bf]">/</span> <span class="font-medium text-[#39443b]">Overview</span></p>
                    <div class="flex items-center gap-3 sm:gap-4">
                        <p class="text-xs text-[#7d877e]">{{ now()->format('D, M j, Y') }}</p>
                        <span class="flex size-8 items-center justify-center rounded-full border border-[#e4e8e3] bg-[#f7f8f6] text-[10px] font-semibold text-[#536052]" aria-label="Rice mill workspace">RM</span>
                    </div>
                </div>
                <nav aria-label="Section navigation" class="mt-4 flex gap-2 overflow-x-auto pb-0.5 lg:hidden">
                    <a href="#overview" class="shrink-0 rounded-md bg-rice-50 px-3 py-1.5 text-xs font-semibold text-rice-700">Overview</a>
                    <a href="#inventory" class="shrink-0 rounded-md px-3 py-1.5 text-xs text-[#657066] hover:bg-[#f5f7f4]">Inventory</a>
                    <a href="#recent-sales" class="shrink-0 rounded-md px-3 py-1.5 text-xs text-[#657066] hover:bg-[#f5f7f4]">Sales</a>
                    <a href="#production" class="shrink-0 rounded-md px-3 py-1.5 text-xs text-[#657066] hover:bg-[#f5f7f4]">Production</a>
                </nav>
            </header>

            <main id="overview" class="mx-auto max-w-360 px-5 py-8 sm:px-8 sm:py-10 lg:px-10">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-normal text-rice-700">Mill operations</p>
                        <h1 class="mt-2 text-2xl font-semibold tracking-normal text-[#202821] sm:text-[30px]">Operations overview</h1>
                        <p class="mt-2 text-sm text-[#778178]">A clear view of stock, production and business activity.</p>
                    </div>
                    <a href="#recent-sales" class="inline-flex min-h-10 items-center justify-center gap-2 self-start rounded-md bg-rice-700 px-4 text-sm font-medium text-white transition hover:bg-rice-600 sm:self-auto">
                        Review sales <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <section aria-label="Business summary" class="mt-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm text-[#69746a]">Sales this month</p>
                            <span class="size-2 rounded-full bg-[#43845b]"></span>
                        </div>
                        <p class="mt-4 text-[27px] font-semibold tabular-nums tracking-normal text-[#202821]">{{ number_format((float) $monthlySales, 0) }}</p>
                        <p class="mt-1.5 text-xs text-[#929b92]">Recorded sale value</p>
                    </article>
                    <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm text-[#69746a]">Paddy purchases</p>
                            <span class="size-2 rounded-full bg-[#c58a3b]"></span>
                        </div>
                        <p class="mt-4 text-[27px] font-semibold tabular-nums tracking-normal text-[#202821]">{{ number_format((float) $monthlyPurchases, 0) }}</p>
                        <p class="mt-1.5 text-xs text-[#929b92]">Purchase value this month</p>
                    </article>
                    <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm text-[#69746a]">Open batches</p>
                            <span class="size-2 rounded-full bg-[#d47a57]"></span>
                        </div>
                        <p class="mt-4 text-[27px] font-semibold tabular-nums tracking-normal text-[#202821]">{{ number_format($openBatchCount) }}</p>
                        <p class="mt-1.5 text-xs text-[#929b92]">Production without an end date</p>
                    </article>
                    <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm text-[#69746a]">Products</p>
                            <span class="size-2 rounded-full bg-[#70849a]"></span>
                        </div>
                        <p class="mt-4 text-[27px] font-semibold tabular-nums tracking-normal text-[#202821]">{{ number_format($productCount) }}</p>
                        <p class="mt-1.5 text-xs text-[#929b92]">Across the product catalog</p>
                    </article>
                </section>

                <div class="mt-8 grid gap-5 xl:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.95fr)]">
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

                    <section id="inventory" class="min-w-0 rounded-lg border border-[#e4e9e2] bg-white">
                        <div class="flex items-center justify-between gap-3 border-b border-[#edf0ec] px-5 py-4 sm:px-6">
                            <div>
                                <h2 class="text-sm font-semibold text-[#273129]">Stock at a glance</h2>
                                <p class="mt-1 text-xs text-[#909990]">Lowest on-hand quantities</p>
                            </div>
                            <span class="text-[11px] text-[#929b92]">{{ $stockProducts->count() }} products</span>
                        </div>
                        @if ($stockProducts->isNotEmpty())
                            <ul class="divide-y divide-[#eff2ee]">
                                @foreach ($stockProducts as $product)
                                    <li class="flex items-center justify-between gap-3 px-5 py-4 sm:px-6">
                                        <div class="min-w-0">
                                            <p class="truncate text-xs font-medium text-[#344137]">{{ $product->name }}</p>
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
                </div>

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

                <footer class="pb-2 pt-8 text-center text-[11px] text-[#a0a9a0]">Rice Mill Operations <span class="px-1.5">·</span> {{ now()->format('Y') }}</footer>
            </main>
        </div>
    </div>
</body>
</html>