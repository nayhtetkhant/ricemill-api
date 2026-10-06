@extends('layouts.app')

@section('title', 'Record purchase')

@section('content')
    <div class="mb-6">
        <a href="{{ route('purchases.index') }}" class="text-xs font-medium text-rice-700 hover:underline">&larr; Purchases</a>
        <h1 class="mt-3 text-2xl font-semibold text-[#202821]">Record paddy purchase</h1>
        <p class="mt-2 text-sm text-[#778178]">Received quantity is added to stock after the receipt is saved.</p>
    </div>
    @include('purchases._form')
@endsection