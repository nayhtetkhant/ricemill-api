@extends('layouts.app')

@section('title', 'Operations overview')

@section('content')
    <div id="overview" class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-normal text-rice-700">Mill operations</p>
            <h1 class="mt-2 text-2xl font-semibold tracking-normal text-[#202821] sm:text-[30px]">Operations overview</h1>
            <p class="mt-2 text-sm text-[#778178]">A clear view of stock, production and business activity.</p>
        </div>
        <div class="flex flex-wrap gap-2 self-start sm:self-auto">
            <a href="{{ route('purchases.create') }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-[#dfe5dd] bg-white px-4 text-sm font-medium text-[#536052] transition hover:bg-[#f5f7f4]">Record purchase</a>
            <a href="{{ route('sales.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-md bg-rice-700 px-4 text-sm font-medium text-white transition hover:bg-rice-600">New sale <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>

    @include('dashboard._summary')

    <div class="mt-8 grid gap-5 xl:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.95fr)]">
        @include('dashboard._recent-sales')
        @include('dashboard._inventory')
    </div>

    @include('dashboard._recent-purchases')
    @include('dashboard._production')

    <footer class="pb-2 pt-8 text-center text-[11px] text-[#a0a9a0]">Rice Mill Operations <span class="px-1.5">·</span> {{ now()->format('Y') }}</footer>
@endsection
