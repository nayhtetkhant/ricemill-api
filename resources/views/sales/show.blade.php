@extends('layouts.app')

@section('title', $sale->invoice_number)

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <a href="{{ route('sales.index') }}" class="text-xs font-medium text-rice-700 hover:underline">&larr; Sales</a>
            <h1 class="mt-3 text-2xl font-semibold text-[#202821]">{{ $sale->invoice_number }}</h1>
            <p class="mt-2 text-sm text-[#778178]">{{ $sale->customer->name }} <span class="px-1.5 text-[#c0c7bf]">·</span> {{ $sale->sale_date->format('M j, Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('sales.edit', $sale) }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#dfe5dd] px-4 text-sm font-medium text-[#536052] hover:bg-[#f5f7f4]">Edit</a>
            <form method="POST" action="{{ route('sales.destroy', $sale) }}" onsubmit="return confirm('Void this invoice and restore its sold stock?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#edd5cc] px-4 text-sm font-medium text-[#984f37] hover:bg-[#fff4ef]">Void sale</button>
            </form>
        </div>
    </div>

    <section class="mt-7 overflow-hidden rounded-lg border border-[#e4e9e2] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-140 text-left text-xs">
                <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                    <tr><th class="px-6 py-3">Product</th><th class="px-4 py-3 text-right">Quantity</th><th class="px-4 py-3 text-right">Unit price</th><th class="px-6 py-3 text-right">Subtotal</th></tr>
                </thead>
                <tbody class="divide-y divide-[#eff2ee]">
                    @foreach ($sale->items as $item)
                        <tr>
                            <td class="px-6 py-4 font-medium text-[#344137]">{{ $item->product->name }} <span class="font-normal text-[#929b92]">{{ $item->product->code }}</span></td>
                            <td class="whitespace-nowrap px-4 py-4 text-right tabular-nums text-[#59645b]">{{ number_format((float) $item->quantity, 2) }} {{ $item->product->unit }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-right tabular-nums text-[#59645b]">{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right font-medium tabular-nums text-[#344137]">{{ number_format((float) $item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-[#e9ede8] bg-[#fafbf9]">
                    <tr><td colspan="3" class="px-6 py-4 text-right text-xs font-medium text-[#59645b]">Invoice total</td><td class="px-6 py-4 text-right text-sm font-semibold tabular-nums text-[#202821]">{{ number_format((float) $sale->total_amount, 2) }}</td></tr>
                </tfoot>
            </table>
        </div>
        <dl class="grid border-t border-[#edf0ec] sm:grid-cols-3">
            <div class="px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Paid</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ number_format((float) $sale->paid_amount, 2) }}</dd></div>
            <div class="px-5 py-4 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Payment status</dt><dd class="mt-1 text-sm font-medium text-[#344137]">{{ str($sale->payment_status)->headline() }}</dd></div>
            @if ($sale->notes)
                <div class="px-5 py-4 sm:col-span-3 sm:px-6"><dt class="text-[11px] text-[#8b958c]">Notes</dt><dd class="mt-1 whitespace-pre-line text-sm text-[#59645b]">{{ $sale->notes }}</dd></div>
            @endif
        </dl>
    </section>
@endsection