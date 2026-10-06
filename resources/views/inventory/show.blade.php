@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <a href="{{ route('inventory.index') }}" class="text-xs font-medium text-rice-700 hover:underline">&larr; Inventory</a>
            <h1 class="mt-3 text-2xl font-semibold text-[#202821]">{{ $product->name }}</h1>
            <p class="mt-2 text-sm text-[#778178]">{{ $product->code }} <span class="px-1.5 text-[#c0c7bf]">·</span> {{ match ($product->type) {
                \App\Enums\ProductType::RawMaterial => 'Raw material',
                \App\Enums\ProductType::Finished => 'Finished product',
                \App\Enums\ProductType::ByProduct => 'By-product',
            } }}</p>
        </div>
        @if ($product->type === \App\Enums\ProductType::RawMaterial)
            <a href="{{ route('purchases.create') }}" class="inline-flex min-h-10 items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-medium text-white hover:bg-rice-600">Record paddy purchase</a>
        @endif
    </div>

    <section aria-label="Product stock summary" class="mt-7 grid gap-3 sm:grid-cols-3">
        <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
            <p class="text-sm text-[#69746a]">On hand</p>
            <p class="mt-3 text-2xl font-semibold tabular-nums text-[#202821]">{{ number_format((float) $product->current_stock, 2) }} <span class="text-sm font-medium text-[#778178]">{{ $product->unit }}</span></p>
        </article>
        <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
            <p class="text-sm text-[#69746a]">Unit price</p>
            <p class="mt-3 text-2xl font-semibold tabular-nums text-[#202821]">{{ number_format((float) $product->unit_price, 2) }}</p>
        </article>
        <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
            <p class="text-sm text-[#69746a]">Stock status</p>
            <p class="mt-3 text-sm font-semibold {{ (float) $product->current_stock > 0 ? 'text-rice-600' : 'text-[#984f37]' }}">{{ (float) $product->current_stock > 0 ? 'Available' : 'Out of stock' }}</p>
        </article>
    </section>

    <section class="mt-7 overflow-hidden rounded-lg border border-[#e4e9e2] bg-white">
        <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6">
            <h2 class="text-sm font-semibold text-[#273129]">Stock movement history</h2>
            <p class="mt-1 text-xs text-[#909990]">Recorded receipts, sales, production and adjustments</p>
        </div>
        @if ($movements->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full min-w-140 text-left text-xs">
                    <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                        <tr><th class="px-6 py-3">Date</th><th class="px-4 py-3">Movement</th><th class="px-4 py-3 text-right">Quantity</th><th class="px-4 py-3 text-right">Balance after</th><th class="px-6 py-3">Notes</th></tr>
                    </thead>
                    <tbody class="divide-y divide-[#eff2ee]">
                        @foreach ($movements as $movement)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-[#7e887f]">{{ $movement->created_at->format('M j, Y H:i') }}</td>
                                <td class="px-4 py-4 font-medium text-[#344137]">{{ str($movement->type)->replace('_', ' ')->headline() }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-right font-medium tabular-nums {{ (float) $movement->quantity < 0 ? 'text-[#984f37]' : 'text-rice-600' }}">{{ number_format((float) $movement->quantity, 2) }} {{ $product->unit }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-right tabular-nums text-[#344137]">{{ number_format((float) $movement->balance_after, 2) }} {{ $product->unit }}</td>
                                <td class="px-6 py-4 text-[#7e887f]">{{ $movement->notes ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#edf0ec] px-5 py-4 sm:px-6">{{ $movements->links() }}</div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-sm font-medium text-[#536054]">No stock movements yet</p>
                <p class="mt-1 text-xs text-[#939c94]">Inventory activity will appear here when stock changes are recorded.</p>
            </div>
        @endif
    </section>
@endsection