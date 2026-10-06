@extends('layouts.app')

@section('title', 'Edit purchase')

@section('content')
    <div class="mb-6">
        <a href="{{ route('purchases.show', $purchase) }}" class="text-xs font-medium text-rice-700 hover:underline">&larr; {{ $purchase->purchase_number }}</a>
        <h1 class="mt-3 text-2xl font-semibold text-[#202821]">Edit purchase</h1>
        <p class="mt-2 text-sm text-[#778178]">The previous stock movement will be reversed before the updated receipt is applied.</p>
    </div>
    @include('purchases._form', ['purchase' => $purchase])
@endsection