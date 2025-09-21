<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('dark-mode') === 'true' }" x-init="$watch('dark', value => localStorage.setItem('dark-mode', value))" :class="{ 'dark': dark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'VirtualNumberPlatform') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </head>
    <body class="bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100 min-h-screen">
        <div class="min-h-screen flex flex-col">
            <nav class="bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-slate-200 dark:border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <a href="{{ route('dashboard') }}" class="text-lg font-semibold">{{ config('app.name') }}</a>
                        <div class="flex items-center gap-4">
                            <button @click="dark = !dark" class="px-3 py-1 rounded border border-slate-300 dark:border-slate-700 text-sm">
                                <span x-show="!dark">🌙 Dark</span>
                                <span x-show="dark">☀️ Light</span>
                            </button>
                            <div class="hidden md:flex gap-3 text-sm">
                                <a class="hover:text-indigo-500" href="{{ route('dashboard') }}">Dashboard</a>
                                @if(auth()->user()?->isAdmin())
                                    <a class="hover:text-indigo-500" href="{{ route('admin.dashboard') }}">Admin</a>
                                @else
                                    <a class="hover:text-indigo-500" href="{{ route('customer.numbers.index') }}">My Numbers</a>
                                    <a class="hover:text-indigo-500" href="{{ route('customer.wallet') }}">Wallet</a>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="px-3 py-1 rounded bg-indigo-600 text-white text-sm">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-1">
                <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                    @if (session('status'))
                        <div class="mb-4 rounded border border-green-500 bg-green-100/60 dark:bg-green-900/40 px-4 py-3 text-sm text-green-700 dark:text-green-200">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ $slot ?? '' }}
                </div>
            </main>

            <footer class="border-t border-slate-200 dark:border-slate-800 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
                Virtual Number Platform &copy; {{ date('Y') }}
            </footer>
        </div>
    </body>
</html>
