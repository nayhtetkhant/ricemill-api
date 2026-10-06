@extends('layouts.app')

@section('title', 'Sales')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-normal text-rice-700">Customer invoices</p>
            <h1 class="mt-2 text-2xl font-semibold text-[#202821]">Sales</h1>
            <p class="mt-2 text-sm text-[#778178]">Invoices, payment progress and finished-goods movement.</p>
        </div>
        <a href="{{ route('sales.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 self-start rounded-md bg-rice-700 px-4 text-sm font-medium text-white transition hover:bg-rice-600 sm:self-auto"><span aria-hidden="true">+</span> New sale</a>
    </div>

    <section class="mt-7 overflow-hidden rounded-lg border border-[#e4e9e2] bg-white">
        @if ($sales->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full min-w-180 text-left text-xs">
                    <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                        <tr>
                            <th class="px-6 py-3">Invoice</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Lines</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-6 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#eff2ee]">
                        @foreach ($sales as $sale)
                            <tr class="hover:bg-[#fbfcfa]">
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-[#344137]"><a class="hover:text-rice-700" href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a></td>
                                <td class="px-4 py-4 text-[#59645b]">{{ $sale->customer->name }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-[#7e887f]">{{ $sale->sale_date->format('M j, Y') }}</td>
                                <td class="px-4 py-4 text-[#59645b]">{{ $sale->items_count }} {{ str('line')->plural($sale->items_count) }}</td>
                                <td class="px-4 py-4"><span class="inline-flex rounded px-2 py-1 text-[10px] font-medium {{ $sale->payment_status === 'paid' ? 'bg-[#edf6ef] text-[#39734e]' : ($sale->payment_status === 'partial' ? 'bg-[#fff5e8] text-[#9b6726]' : 'bg-[#f4f1ed] text-[#776d60]') }}">{{ str($sale->payment_status)->headline() }}</span></td>
                                <td class="whitespace-nowrap px-6 py-4 text-right font-semibold tabular-nums text-[#344137]">{{ number_format((float) $sale->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#edf0ec] px-5 py-4 sm:px-6">{{ $sales->links() }}</div>
        @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-medium text-[#536054]">No sales recorded yet</p>
                <p class="mt-1 text-xs text-[#939c94]">Create an invoice to record a customer sale and deduct inventory.</p>
                <a href="{{ route('sales.create') }}" class="mt-5 inline-flex min-h-10 items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-medium text-white hover:bg-rice-600">New sale</a>
            </div>
        @endif
    </section>
@endsection