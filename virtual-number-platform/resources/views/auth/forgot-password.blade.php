<x-guest-layout>
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <h2 class="text-xl font-semibold text-center">Reset your password</h2>
        <p class="text-sm text-slate-500 text-center">Enter your email and we will send you a link to reset your password.</p>
        @if (session('status'))
            <div class="rounded border border-green-500 bg-green-100 px-3 py-2 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        <div>
            <x-label for="email" value="Email" />
            <x-input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
            @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <x-button class="w-full">Send reset link</x-button>
    </form>
</x-guest-layout>
