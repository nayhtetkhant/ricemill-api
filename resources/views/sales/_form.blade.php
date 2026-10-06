@php
    $sale = $sale ?? null;
    $defaultItems = $sale
        ? $sale->items->map(fn ($item): array => [
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
        ])->values()->all()
        : [['product_id' => '', 'quantity' => '', 'unit_price' => '']];
    $formItems = old('items', $defaultItems);
@endphp

<form method="POST" action="{{ $sale ? route('sales.update', $sale) : route('sales.store') }}" class="rounded-lg border border-[#e4e9e2] bg-white p-5 sm:p-7">
    @csrf
    @if ($sale) @method('PUT') @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="customer_id" class="mb-1.5 block text-xs font-medium text-[#465148]">Customer</label>
            <select id="customer_id" name="customer_id" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                <option value="">Select customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" @selected((string) old('customer_id', $sale?->customer_id) === (string) $customer->id)>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="sale_date" class="mb-1.5 block text-xs font-medium text-[#465148]">Sale date</label>
            <input id="sale_date" name="sale_date" type="date" value="{{ old('sale_date', $sale?->sale_date?->toDateString() ?? now()->toDateString()) }}" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
    </div>

    <div class="mt-8 flex items-center justify-between gap-3 border-b border-[#edf0ec] pb-3">
        <div>
            <h2 class="text-sm font-semibold text-[#273129]">Invoice items</h2>
            <p class="mt-1 text-xs text-[#909990]">Choose finished products or by-products available in inventory.</p>
        </div>
        <button id="add-line" type="button" class="shrink-0 rounded-md border border-[#dfe5dd] px-3 py-2 text-xs font-medium text-[#536052] hover:bg-[#f5f7f4]">Add line</button>
    </div>

    <div id="sale-lines" class="divide-y divide-[#eff2ee]">
        @foreach ($formItems as $index => $item)
            <div class="sale-line grid gap-3 py-4 sm:grid-cols-[minmax(0,1.6fr)_minmax(100px,0.7fr)_minmax(120px,0.8fr)_minmax(100px,0.7fr)_auto] sm:items-end">
                <div class="min-w-0">
                    <label class="mb-1.5 block text-xs font-medium text-[#465148]">Product</label>
                    <select name="items[{{ $index }}][product_id]" required class="line-product w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                        <option value="">Select product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}" @selected((string) ($item['product_id'] ?? '') === (string) $product->id)>{{ $product->name }} · {{ $product->code }} ({{ number_format((float) $product->current_stock, 2) }} {{ $product->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-[#465148]">Quantity</label>
                    <input name="items[{{ $index }}][quantity]" type="number" min="0.01" step="0.01" value="{{ $item['quantity'] ?? '' }}" required class="line-quantity w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-[#465148]">Unit price</label>
                    <input name="items[{{ $index }}][unit_price]" type="number" min="0" step="0.01" value="{{ $item['unit_price'] ?? '' }}" required class="line-price w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                </div>
                <div class="pb-2 text-right text-xs font-medium tabular-nums text-[#59645b]"><span class="line-total">0.00</span></div>
                <button type="button" class="remove-line mb-1 inline-flex size-9 items-center justify-center rounded-md border border-[#e7e9e5] text-sm text-[#8c6559] hover:bg-[#fff4ef]" aria-label="Remove invoice line" title="Remove line">&times;</button>
            </div>
        @endforeach
    </div>

    <template id="sale-line-template">
        <div class="sale-line grid gap-3 py-4 sm:grid-cols-[minmax(0,1.6fr)_minmax(100px,0.7fr)_minmax(120px,0.8fr)_minmax(100px,0.7fr)_auto] sm:items-end">
            <div class="min-w-0">
                <label class="mb-1.5 block text-xs font-medium text-[#465148]">Product</label>
                <select name="items[__INDEX__][product_id]" required class="line-product w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                    <option value="">Select product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}">{{ $product->name }} · {{ $product->code }} ({{ number_format((float) $product->current_stock, 2) }} {{ $product->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div><label class="mb-1.5 block text-xs font-medium text-[#465148]">Quantity</label><input name="items[__INDEX__][quantity]" type="number" min="0.01" step="0.01" required class="line-quantity w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100"></div>
            <div><label class="mb-1.5 block text-xs font-medium text-[#465148]">Unit price</label><input name="items[__INDEX__][unit_price]" type="number" min="0" step="0.01" required class="line-price w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100"></div>
            <div class="pb-2 text-right text-xs font-medium tabular-nums text-[#59645b]"><span class="line-total">0.00</span></div>
            <button type="button" class="remove-line mb-1 inline-flex size-9 items-center justify-center rounded-md border border-[#e7e9e5] text-sm text-[#8c6559] hover:bg-[#fff4ef]" aria-label="Remove invoice line" title="Remove line">&times;</button>
        </div>
    </template>

    <div class="mt-2 grid gap-5 border-t border-[#edf0ec] pt-5 sm:grid-cols-2">
        <div>
            <label for="paid_amount" class="mb-1.5 block text-xs font-medium text-[#465148]">Amount paid</label>
            <input id="paid_amount" name="paid_amount" type="number" min="0" step="0.01" value="{{ old('paid_amount', $sale?->paid_amount ?? 0) }}" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
        <div class="flex items-end justify-between rounded-md bg-[#f7f9f6] px-4 py-3">
            <span class="text-xs font-medium text-[#59645b]">Invoice total preview</span>
            <strong id="invoice-total" class="text-lg font-semibold tabular-nums text-[#202821]">0.00</strong>
        </div>
        <div class="sm:col-span-2">
            <label for="notes" class="mb-1.5 block text-xs font-medium text-[#465148]">Notes <span class="font-normal text-[#909990]">Optional</span></label>
            <textarea id="notes" name="notes" rows="3" class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">{{ old('notes', $sale?->notes) }}</textarea>
        </div>
    </div>

    <div class="mt-7 flex flex-col-reverse gap-3 border-t border-[#edf0ec] pt-5 sm:flex-row sm:justify-end">
        <a href="{{ $sale ? route('sales.show', $sale) : route('sales.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#dfe5dd] px-4 text-sm font-medium text-[#536052] hover:bg-[#f5f7f4]">Cancel</a>
        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-medium text-white hover:bg-rice-600">{{ $sale ? 'Save invoice' : 'Create invoice' }}</button>
    </div>
</form>

@push('scripts')
    <script>
        (() => {
            const lines = document.getElementById('sale-lines');
            const template = document.getElementById('sale-line-template');
            const totalOutput = document.getElementById('invoice-total');
            let nextIndex = lines.querySelectorAll('.sale-line').length;

            const updateTotals = () => {
                let invoiceTotal = 0;

                lines.querySelectorAll('.sale-line').forEach((line) => {
                    const quantity = Number(line.querySelector('.line-quantity').value || 0);
                    const price = Number(line.querySelector('.line-price').value || 0);
                    const lineTotal = quantity * price;
                    line.querySelector('.line-total').textContent = lineTotal.toFixed(2);
                    invoiceTotal += lineTotal;
                });

                totalOutput.textContent = invoiceTotal.toFixed(2);
            };

            document.getElementById('add-line').addEventListener('click', () => {
                const fragment = template.content.cloneNode(true);
                fragment.querySelectorAll('[name]').forEach((field) => {
                    field.name = field.name.replaceAll('__INDEX__', String(nextIndex));
                });
                lines.append(fragment);
                nextIndex += 1;
                updateTotals();
            });

            lines.addEventListener('input', updateTotals);
            lines.addEventListener('change', (event) => {
                if (event.target.matches('.line-product')) {
                    const selected = event.target.selectedOptions[0];
                    const priceInput = event.target.closest('.sale-line').querySelector('.line-price');
                    if (selected?.dataset.price) {
                        priceInput.value = Number(selected.dataset.price).toFixed(2);
                    }
                }

                updateTotals();
            });

            lines.addEventListener('click', (event) => {
                const removeButton = event.target.closest('.remove-line');
                if (!removeButton) {
                    return;
                }

                const currentLines = lines.querySelectorAll('.sale-line');
                if (currentLines.length === 1) {
                    currentLines[0].querySelectorAll('input').forEach((input) => input.value = '');
                    currentLines[0].querySelector('select').selectedIndex = 0;
                } else {
                    removeButton.closest('.sale-line').remove();
                }

                updateTotals();
            });

            updateTotals();
        })();
    </script>
@endpush