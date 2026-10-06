@extends('layouts.app')

@section('title', 'New sale')

@section('content')
    <div class="mb-6">
        <a href="{{ route('sales.index') }}" class="text-xs font-medium text-rice-700 hover:underline">&larr; Sales</a>
        <h1 class="mt-3 text-2xl font-semibold text-[#202821]">Create sale</h1>
        <p class="mt-2 text-sm text-[#778178]">Each invoice line reserves stock when the sale is saved.</p>
    </div>
    @include('sales._form')
@endsection