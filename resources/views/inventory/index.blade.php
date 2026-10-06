@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-normal text-rice-700">Stock control</p>
            <h1 class="mt-2 text-2xl font-semibold text-[#202821]">Inventory</h1>
            <p class="mt-2 text-sm text-[#778178]">Products, on-hand quantities and recent stock activity.</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="inline-flex min-h-10 items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-medium text-white transition hover:bg-rice-600">Record paddy purchase</a>
    </div>

    <section aria-label="Inventory summary" class="mt-7 grid gap-3 sm:grid-cols-2">
        <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
            <p class="text-sm text-[#69746a]">Products in catalog</p>
            <p class="mt-3 text-2xl font-semibold tabular-nums text-[#202821]">{{ number_format($productCount) }}</p>
        </article>
        <article class="rounded-lg border border-[#e4e9e2] bg-white p-5">
            <p class="text-sm text-[#69746a]">Out of stock</p>
            <p class="mt-3 text-2xl font-semibold tabular-nums text-[#202821]">{{ number_format($zeroStockCount) }}</p>
        </article>
    </section>

    <form method="GET" action="{{ route('inventory.index') }}" class="mt-6 grid gap-3 rounded-lg border border-[#e4e9e2] bg-white p-4 sm:grid-cols-[minmax(0,1fr)_200px_180px_auto_auto] sm:items-end">
        <div>
            <label for="search" class="mb-1.5 block text-xs font-medium text-[#465148]">Search products</label>
            <input id="search" name="search" type="search" value="{{ $filters['search'] }}" placeholder="Name or product code" class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
        <div>
            <label for="type" class="mb-1.5 block text-xs font-medium text-[#465148]">Product type</label>
            <select id="type" name="type" class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                <option value="">All types</option>
                @foreach (\App\Enums\ProductType::cases() as $type)
                    <option value="{{ $type->value }}" @selected($filters['type'] === $type->value)>{{ match ($type) {
                        \App\Enums\ProductType::RawMaterial => 'Raw material',
                        \App\Enums\ProductType::Finished => 'Finished product',
                        \App\Enums\ProductType::ByProduct => 'By-product',
                    } }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="stock" class="mb-1.5 block text-xs font-medium text-[#465148]">Stock status</label>
            <select id="stock" name="stock" class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                <option value="all" @selected($filters['stock'] === 'all')>All stock</option>
                <option value="available" @selected($filters['stock'] === 'available')>Available</option>
                <option value="zero" @selected($filters['stock'] === 'zero')>Out of stock</option>
            </select>
        </div>
        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-medium text-white hover:bg-rice-600">Apply</button>
        <a href="{{ route('inventory.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#dfe5dd] px-4 text-sm font-medium text-[#536052] hover:bg-[#f5f7f4]">Clear</a>
    </form>

    <section class="mt-5 overflow-hidden rounded-lg border border-[#e4e9e2] bg-white">
        @if ($products->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full min-w-180 text-left text-xs">
                    <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                        <tr>
                            <th class="px-6 py-3">Product</th>
                            <th class="px-4 py-3">Code</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3 text-right">On hand</th>
                            <th class="px-4 py-3 text-right">Unit price</th>
                            <th class="px-6 py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#eff2ee]">
                        @foreach ($products as $product)
                            <tr class="hover:bg-[#fbfcfa]">
                                <td class="px-6 py-4 font-medium text-[#344137]"><a href="{{ route('inventory.show', $product) }}" class="hover:text-rice-700">{{ $product->name }}</a></td>
                                <td class="whitespace-nowrap px-4 py-4 text-[#7e887f]">{{ $product->code }}</td>
                                <td class="px-4 py-4 text-[#59645b]">{{ match ($product->type) {
                                    \App\Enums\ProductType::RawMaterial => 'Raw material',
                                    \App\Enums\ProductType::Finished => 'Finished product',
                                    \App\Enums\ProductType::ByProduct => 'By-product',
                                } }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-right font-semibold tabular-nums text-[#344137]">{{ number_format((float) $product->current_stock, 2) }} {{ $product->unit }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-right tabular-nums text-[#59645b]">{{ number_format((float) $product->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-right"><span class="inline-flex rounded px-2 py-1 text-[10px] font-medium {{ (float) $product->current_stock > 0 ? 'bg-[#edf6ef] text-rice-600' : 'bg-[#fff4ef] text-[#984f37]' }}">{{ (float) $product->current_stock > 0 ? 'Available' : 'Out of stock' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#edf0ec] px-5 py-4 sm:px-6">{{ $products->links() }}</div>
        @else
            <div class="px-6 py-14 text-center">
                <p class="text-sm font-medium text-[#536054]">No matching products</p>
                <p class="mt-1 text-xs text-[#939c94]">Try a different product name, code or stock filter.</p>
            </div>
        @endif
    </section>
@endsection