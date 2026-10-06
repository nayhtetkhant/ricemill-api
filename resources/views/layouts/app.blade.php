<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f4">
    <title>@yield('title', 'Operations') | {{ config('app.name', 'Rice Mill') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f7f4] font-sans text-[#202821] antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="hidden w-64 shrink-0 flex-col border-r border-[#e3e8e1] bg-white lg:sticky lg:top-0 lg:flex lg:h-screen">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 border-b border-[#edf0ec] px-6 py-6">
                <span class="flex size-10 items-center justify-center rounded-lg bg-rice-700 text-sm font-bold text-white">RM</span>
                <span>
                    <span class="block text-sm font-semibold">Rice Mill</span>
                    <span class="mt-0.5 block text-xs text-[#7d877e]">Operations</span>
                </span>
            </a>
            <div class="px-4 pt-7">
                <p class="px-3 text-[10px] font-semibold uppercase tracking-normal text-[#a0a9a0]">Workspace</p>
                <nav aria-label="Main navigation" class="mt-3 flex flex-col gap-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm {{ request()->routeIs('dashboard') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#59645b] hover:bg-[#f6f8f5]' }}">Overview</a>
                    <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm {{ request()->routeIs('inventory.*') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#59645b] hover:bg-[#f6f8f5]' }}">Inventory</a>
                    <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm {{ request()->routeIs('purchases.*') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#59645b] hover:bg-[#f6f8f5]' }}">Paddy purchases</a>
                    <a href="{{ route('sales.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm {{ request()->routeIs('sales.*') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#59645b] hover:bg-[#f6f8f5]' }}">Sales</a>
                </nav>
            </div>
            <div class="mt-auto border-t border-[#edf0ec] px-6 py-5">
                <p class="truncate text-xs font-medium text-[#59645b]">{{ auth()->user()->name }}</p>
                <p class="mt-1 text-[11px] text-[#9aa39a]">{{ str(auth()->user()->role->value)->headline() }}</p>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="border-b border-[#e3e8e1] bg-white px-5 py-4 sm:px-8 lg:px-10">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 lg:hidden">
                        <span class="flex size-8 items-center justify-center rounded-md bg-rice-700 text-xs font-bold text-white">RM</span>
                        <span class="text-sm font-semibold">Rice Mill</span>
                    </a>
                    <p class="hidden text-xs text-[#879187] sm:block">Operations <span class="px-1.5 text-[#c0c7bf]">/</span> <span class="font-medium text-[#39443b]">@yield('title', 'Operations')</span></p>
                    <div class="flex items-center gap-3">
                        <span class="hidden text-xs text-[#7d877e] sm:inline">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md border border-[#dfe5dd] px-3 py-2 text-xs font-medium text-[#536052] transition hover:bg-[#f5f7f4]">Sign out</button>
                        </form>
                    </div>
                </div>
                <nav aria-label="Section navigation" class="mt-4 flex gap-2 overflow-x-auto pb-0.5 lg:hidden">
                    <a href="{{ route('dashboard') }}" class="shrink-0 rounded-md px-3 py-1.5 text-xs {{ request()->routeIs('dashboard') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#657066] hover:bg-[#f5f7f4]' }}">Overview</a>
                    <a href="{{ route('inventory.index') }}" class="shrink-0 rounded-md px-3 py-1.5 text-xs {{ request()->routeIs('inventory.*') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#657066] hover:bg-[#f5f7f4]' }}">Inventory</a>
                    <a href="{{ route('purchases.index') }}" class="shrink-0 rounded-md px-3 py-1.5 text-xs {{ request()->routeIs('purchases.*') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#657066] hover:bg-[#f5f7f4]' }}">Purchases</a>
                    <a href="{{ route('sales.index') }}" class="shrink-0 rounded-md px-3 py-1.5 text-xs {{ request()->routeIs('sales.*') ? 'bg-rice-50 font-semibold text-rice-700' : 'text-[#657066] hover:bg-[#f5f7f4]' }}">Sales</a>
                </nav>
            </header>

            <main class="mx-auto max-w-360 px-5 py-8 sm:px-8 sm:py-10 lg:px-10">
                @if (session('status'))
                    <div role="status" class="mb-6 rounded-md border border-[#cfe2d2] bg-[#edf6ef] px-4 py-3 text-sm text-[#356a46]">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-md border border-[#efd4ca] bg-[#fff4ef] px-4 py-3 text-sm text-[#984f37]">
                        <p class="font-semibold">Please check the highlighted information.</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>