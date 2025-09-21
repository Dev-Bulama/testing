<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <h2 class="text-xl font-semibold text-center">Choose a new password</h2>
        <div>
            <x-label for="email" value="Email" />
            <x-input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus />
            @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <x-label for="password" value="Password" />
            <x-input id="password" name="password" type="password" required />
            @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <x-label for="password_confirmation" value="Confirm password" />
            <x-input id="password_confirmation" name="password_confirmation" type="password" required />
        </div>
        <x-button class="w-full">Reset password</x-button>
    </form>
</x-guest-layout>
