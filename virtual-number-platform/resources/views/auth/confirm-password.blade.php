<x-guest-layout>
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf
        <h2 class="text-xl font-semibold text-center">Confirm password</h2>
        <p class="text-sm text-slate-500 text-center">This is a secure area of the application. Please confirm your password before continuing.</p>
        <div>
            <x-label for="password" value="Password" />
            <x-input id="password" name="password" type="password" required autofocus />
            @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <x-button class="w-full">Confirm</x-button>
    </form>
</x-guest-layout>
