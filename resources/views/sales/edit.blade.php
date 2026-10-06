@extends('layouts.app')

@section('title', 'Edit sale')

@section('content')
    <div class="mb-6">
        <a href="{{ route('sales.show', $sale) }}" class="text-xs font-medium text-rice-700 hover:underline">&larr; {{ $sale->invoice_number }}</a>
        <h1 class="mt-3 text-2xl font-semibold text-[#202821]">Edit invoice</h1>
        <p class="mt-2 text-sm text-[#778178]">The old invoice stock movements will be reversed before the replacement lines are applied.</p>
    </div>
    @include('sales._form', ['sale' => $sale])
@endsection