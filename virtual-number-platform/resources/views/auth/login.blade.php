<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <h2 class="text-xl font-semibold text-center">Welcome back</h2>
        @if (session('status'))
            <div class="rounded border border-green-500 bg-green-100 px-3 py-2 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        <div>
            <x-label for="email" value="Email" />
            <x-input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
            @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <x-label for="password" value="Password" />
            <x-input id="password" name="password" type="password" required />
            @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-slate-300" />
                Remember me
            </label>
            <a class="text-indigo-600 hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
        </div>
        <x-button class="w-full">Sign in</x-button>
        <p class="text-center text-sm text-slate-500">New here? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Create an account</a></p>
    </form>
</x-guest-layout>
