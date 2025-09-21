<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'VirtualNumberPlatform') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-100 text-slate-900 min-h-screen">
        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="w-full max-w-md rounded-xl bg-white shadow-xl p-8">
                <div class="text-center mb-6">
                    <a href="/" class="text-2xl font-semibold text-indigo-600">{{ config('app.name') }}</a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
