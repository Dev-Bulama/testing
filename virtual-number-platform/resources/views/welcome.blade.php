<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-slate-950 text-white">
        <div class="relative isolate overflow-hidden">
            <div class="absolute inset-0 opacity-20 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
            <div class="relative mx-auto flex min-h-screen max-w-5xl flex-col items-center justify-center px-6 py-16 text-center">
                <h1 class="text-4xl font-bold sm:text-6xl">Virtual Phone Number Rental &amp; SMS Platform</h1>
                <p class="mt-6 max-w-2xl text-lg text-slate-200">Manage global phone numbers, receive SMS in real-time, and integrate with your applications through a developer-friendly API.</p>
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-500">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-500">Get Started</a>
                        <a href="{{ route('login') }}" class="rounded-lg border border-indigo-400 px-5 py-3 text-sm font-semibold text-indigo-200 hover:bg-indigo-500/10">Sign In</a>
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
