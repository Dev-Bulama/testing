<x-app-layout>
    <x-card>
        <h1 class="text-xl font-semibold mb-4">Add Phone Number</h1>
        <form method="POST" action="{{ route('admin.numbers.store') }}" class="space-y-4">
            @csrf
            <div>
                <x-label value="Provider" />
                <select name="provider_id" class="mt-1 block w-full rounded border border-slate-300 px-3 py-2">
                    @foreach($providers as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-label value="Number" />
                <x-input name="number" required />
            </div>
            <div>
                <x-label value="Country" />
                <x-input name="country" value="US" required />
            </div>
            <div>
                <x-label value="Cost" />
                <x-input name="cost" type="number" step="0.01" value="5" required />
            </div>
            <x-button>Save</x-button>
        </form>
    </x-card>
</x-app-layout>
