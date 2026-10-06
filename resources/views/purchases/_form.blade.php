@php($purchase = $purchase ?? null)
<form method="POST" action="{{ $purchase ? route('purchases.update', $purchase) : route('purchases.store') }}" class="rounded-lg border border-[#e4e9e2] bg-white p-5 sm:p-7">
    @csrf
    @if ($purchase) @method('PUT') @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="supplier_id" class="mb-1.5 block text-xs font-medium text-[#465148]">Supplier</label>
            <select id="supplier_id" name="supplier_id" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                <option value="">Select supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected((string) old('supplier_id', $purchase?->supplier_id) === (string) $supplier->id)>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="product_id" class="mb-1.5 block text-xs font-medium text-[#465148]">Raw paddy product</label>
            <select id="product_id" name="product_id" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                <option value="">Select product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected((string) old('product_id', $purchase?->product_id) === (string) $product->id)>{{ $product->name }} · {{ $product->code }} ({{ $product->unit }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="purchase_date" class="mb-1.5 block text-xs font-medium text-[#465148]">Purchase date</label>
            <input id="purchase_date" name="purchase_date" type="date" value="{{ old('purchase_date', $purchase?->purchase_date?->toDateString() ?? now()->toDateString()) }}" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
        <div>
            <label for="quantity" class="mb-1.5 block text-xs font-medium text-[#465148]">Quantity received</label>
            <input id="quantity" name="quantity" type="number" min="0.01" step="0.01" value="{{ old('quantity', $purchase?->quantity) }}" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
        <div>
            <label for="unit_price" class="mb-1.5 block text-xs font-medium text-[#465148]">Unit price</label>
            <input id="unit_price" name="unit_price" type="number" min="0" step="0.01" value="{{ old('unit_price', $purchase?->unit_price) }}" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
        <div>
            <label for="moisture_percentage" class="mb-1.5 block text-xs font-medium text-[#465148]">Moisture (%) <span class="font-normal text-[#909990]">Optional</span></label>
            <input id="moisture_percentage" name="moisture_percentage" type="number" min="0" max="100" step="0.01" value="{{ old('moisture_percentage', $purchase?->moisture_percentage) }}" class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
        <div>
            <label for="paid_amount" class="mb-1.5 block text-xs font-medium text-[#465148]">Amount paid</label>
            <input id="paid_amount" name="paid_amount" type="number" min="0" step="0.01" value="{{ old('paid_amount', $purchase?->paid_amount ?? 0) }}" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
        </div>
        <div class="sm:col-span-2">
            <label for="notes" class="mb-1.5 block text-xs font-medium text-[#465148]">Notes <span class="font-normal text-[#909990]">Optional</span></label>
            <textarea id="notes" name="notes" rows="3" class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none focus:border-rice-600 focus:ring-2 focus:ring-rice-100">{{ old('notes', $purchase?->notes) }}</textarea>
        </div>
    </div>

    <div class="mt-7 flex flex-col-reverse gap-3 border-t border-[#edf0ec] pt-5 sm:flex-row sm:justify-end">
        <a href="{{ $purchase ? route('purchases.show', $purchase) : route('purchases.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#dfe5dd] px-4 text-sm font-medium text-[#536052] hover:bg-[#f5f7f4]">Cancel</a>
        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-medium text-white hover:bg-rice-600">{{ $purchase ? 'Save changes' : 'Save purchase' }}</button>
    </div>
</form>