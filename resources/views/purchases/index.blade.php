@extends('layouts.app')

@section('title', 'Paddy purchases')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-normal text-rice-700">Procurement</p>
            <h1 class="mt-2 text-2xl font-semibold text-[#202821]">Paddy purchases</h1>
            <p class="mt-2 text-sm text-[#778178]">Receipts, supplier payments and raw paddy stock.</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 self-start rounded-md bg-rice-700 px-4 text-sm font-medium text-white transition hover:bg-rice-600 sm:self-auto"><span aria-hidden="true">+</span> Record purchase</a>
    </div>

    <section class="mt-7 overflow-hidden rounded-lg border border-[#e4e9e2] bg-white">
        @if ($purchases->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full min-w-180 text-left text-xs">
                    <thead class="bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-normal text-[#8b958c]">
                        <tr>
                            <th class="px-6 py-3">Receipt</th>
                            <th class="px-4 py-3">Supplier</th>
                            <th class="px-4 py-3">Raw material</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-6 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#eff2ee]">
                        @foreach ($purchases as $purchase)
                            <tr class="hover:bg-[#fbfcfa]">
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-[#344137]"><a class="hover:text-rice-700" href="{{ route('purchases.show', $purchase) }}">{{ $purchase->purchase_number }}</a></td>
                                <td class="px-4 py-4 text-[#59645b]">{{ $purchase->supplier->name }}</td>
                                <td class="px-4 py-4 text-[#59645b]">{{ $purchase->product->name }} <span class="text-[#969f97]">({{ number_format((float) $purchase->quantity, 2) }} {{ $purchase->product->unit }})</span></td>
                                <td class="whitespace-nowrap px-4 py-4 text-[#7e887f]">{{ $purchase->purchase_date->format('M j, Y') }}</td>
                                <td class="px-4 py-4"><span class="inline-flex rounded px-2 py-1 text-[10px] font-medium {{ $purchase->payment_status === 'paid' ? 'bg-[#edf6ef] text-[#39734e]' : ($purchase->payment_status === 'partial' ? 'bg-[#fff5e8] text-[#9b6726]' : 'bg-[#f4f1ed] text-[#776d60]') }}">{{ str($purchase->payment_status)->headline() }}</span></td>
                                <td class="whitespace-nowrap px-6 py-4 text-right font-semibold tabular-nums text-[#344137]">{{ number_format((float) $purchase->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#edf0ec] px-5 py-4 sm:px-6">{{ $purchases->links() }}</div>
        @else
            <div class="px-6 py-16 text-center">
                <p class="text-sm font-medium text-[#536054]">No purchases recorded yet</p>
                <p class="mt-1 text-xs text-[#939c94]">Record your first paddy receipt to update raw-material stock.</p>
                <a href="{{ route('purchases.create') }}" class="mt-5 inline-flex min-h-10 items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-medium text-white hover:bg-rice-600">Record purchase</a>
            </div>
        @endif
    </section>
@endsection