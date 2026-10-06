@extends('layouts.app')

@section('title', $purchase->purchase_number)

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <a href="{{ route('purchases.index') }}" class="text-xs font-medium text-rice-700 hover:underline">&larr; Purchases</a>
            <h1 class="mt-3 text-2xl font-semibold text-[#202821]">{{ $purchase->purchase_number }}</h1>
            <p class="mt-2 text-sm text-[#778178]">Recorded {{ $purchase->purchase_date->format('M j, Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('purchases.edit', $purchase) }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#dfe5dd] px-4 text-sm font-medium text-[#536052] hover:bg-[#f5f7f4]">Edit</a>
            <form method="POST" action="{{ route('purchases.destroy', $purchase) }}" onsubmit="return confirm('Void this purchase and reverse its remaining stock?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#edd5cc] px-4 text-sm font-medium text-[#984f37] hover:bg-[#fff4ef]">Void purchase</button>
            </form>
        </div>
    </div>

    <section class="mt-7 rounded-lg border border-[#e4e9e2] bg-white">
        <dl class="grid gap-x-8 sm:grid-cols-2">
            <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Supplier</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ $purchase->supplier->name }}</dd></div>
            <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Product</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ $purchase->product->name }}</dd></div>
            <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Quantity received</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ number_format((float) $purchase->quantity, 2) }} {{ $purchase->product->unit }}</dd></div>
            <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Unit price</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ number_format((float) $purchase->unit_price, 2) }}</dd></div>
            <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Total</dt><dd class="mt-1 text-sm font-semibold tabular-nums text-[#202821]">{{ number_format((float) $purchase->total_amount, 2) }}</dd></div>
            <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Paid / status</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ number_format((float) $purchase->paid_amount, 2) }} <span class="text-[#8b958c]">· {{ str($purchase->payment_status)->headline() }}</span></dd></div>
            @if ($purchase->moisture_percentage !== null)
                <div class="border-b border-[#edf0ec] px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Moisture</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ number_format((float) $purchase->moisture_percentage, 2) }}%</dd></div>
            @endif
            @if ($purchase->notes)
                <div class="px-5 py-4 sm:col-span-2 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Notes</dt><dd class="mt-1 whitespace-pre-line text-sm text-[#59645b]">{{ $purchase->notes }}</dd></div>
            @endif
        </dl>
    </section>
@endsection