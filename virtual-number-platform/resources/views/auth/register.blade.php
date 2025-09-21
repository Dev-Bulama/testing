<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <h2 class="text-xl font-semibold text-center">Create your account</h2>
        <div>
            <x-label for="name" value="Name" />
            <x-input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />
            @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <x-label for="email" value="Email" />
            <x-input id="email" name="email" type="email" value="{{ old('email') }}" required />
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
        <x-button class="w-full">Register</x-button>
        <p class="text-center text-sm text-slate-500">Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Sign in</a></p>
    </form>
</x-guest-layout>
