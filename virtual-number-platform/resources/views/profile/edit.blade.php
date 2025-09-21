<x-app-layout>
    <x-card>
        <h2 class="text-lg font-semibold mb-4">Profile</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <x-label for="name" value="Name" />
                <x-input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
            </div>
            <div>
                <x-label value="Email" />
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
            </div>
            <x-button>Save</x-button>
        </form>
    </x-card>
</x-app-layout>
