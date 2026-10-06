<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | {{ config('app.name', 'Rice Mill') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f7f4] font-sans text-[#202821] antialiased">
    <main class="grid min-h-screen lg:grid-cols-[minmax(0,1.1fr)_minmax(420px,0.9fr)]">
        <section class="relative hidden overflow-hidden bg-[#315f43] px-12 py-14 text-white lg:flex lg:flex-col lg:justify-between xl:px-20">
            <div class="absolute inset-0 opacity-20" aria-hidden="true" style="background-image: radial-gradient(#ffffff 0.65px, transparent 0.65px); background-size: 18px 18px;"></div>
            <a href="{{ route('login') }}" class="relative flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-lg bg-white/15 text-sm font-bold">RM</span>
                <span class="text-sm font-semibold">Rice Mill Operations</span>
            </a>
            <div class="relative max-w-xl pb-12">
                <p class="text-xs font-semibold uppercase tracking-normal text-[#d7e8d8]">Production workspace</p>
                <h1 class="mt-5 text-4xl font-semibold leading-tight">A clear view of every grain, batch and sale.</h1>
                <p class="mt-5 max-w-md text-sm leading-6 text-[#e0ebe1]">Sign in to manage paddy receipts, inventory and customer invoices.</p>
            </div>
            <p class="relative text-xs text-[#d7e8d8]">Rice Mill <span class="px-1.5">·</span> {{ now()->format('Y') }}</p>
        </section>

        <section class="flex items-center justify-center px-5 py-12 sm:px-10">
            <div class="w-full max-w-md">
                <a href="{{ route('login') }}" class="flex items-center gap-2.5 lg:hidden">
                    <span class="flex size-9 items-center justify-center rounded-md bg-rice-700 text-xs font-bold text-white">RM</span>
                    <span class="text-sm font-semibold">Rice Mill Operations</span>
                </a>
                <p class="mt-10 text-[11px] font-semibold uppercase tracking-normal text-rice-700 lg:mt-0">Team access</p>
                <h2 class="mt-2 text-2xl font-semibold text-[#202821]">Sign in</h2>
                <p class="mt-2 text-sm text-[#778178]">Use your assigned account to continue.</p>

                <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-1.5 block text-xs font-medium text-[#465148]">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none transition focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                        @error('email') <p class="mt-1.5 text-xs text-[#a64e39]">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="mb-1.5 block text-xs font-medium text-[#465148]">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-md border border-[#dfe5dd] bg-white px-3 py-2.5 text-sm outline-none transition focus:border-rice-600 focus:ring-2 focus:ring-rice-100">
                    </div>
                    <label class="flex items-center gap-2 text-xs text-[#687368]">
                        <input name="remember" type="checkbox" value="1" class="size-4 rounded border-[#cbd4ca] accent-[#39734e]">
                        Keep me signed in
                    </label>
                    <button type="submit" class="flex min-h-11 w-full items-center justify-center rounded-md bg-rice-700 px-4 text-sm font-semibold text-white transition hover:bg-rice-600">Sign in</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>