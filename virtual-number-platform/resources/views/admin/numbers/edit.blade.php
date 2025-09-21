<x-app-layout>
    <x-card>
        <h1 class="text-xl font-semibold mb-4">Edit Phone Number</h1>
        <form method="POST" action="{{ route('admin.numbers.update', $phoneNumber) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <x-label value="Provider" />
                <select name="provider_id" class="mt-1 block w-full rounded border border-slate-300 px-3 py-2">
                    @foreach($providers as $provider)
                        <option value="{{ $provider->id }}" @selected($phoneNumber->provider_id === $provider->id)> {{ $provider->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-label value="Status" />
                <select name="status" class="mt-1 block w-full rounded border border-slate-300 px-3 py-2">
                    <option value="available" @selected($phoneNumber->status === 'available')>Available</option>
                    <option value="assigned" @selected($phoneNumber->status === 'assigned')>Assigned</option>
                    <option value="reserved" @selected($phoneNumber->status === 'reserved')>Reserved</option>
                </select>
            </div>
            <div>
                <x-label value="Cost" />
                <x-input name="cost" type="number" step="0.01" value="{{ $phoneNumber->cost }}" required />
            </div>
            <x-button>Update</x-button>
        </form>
    </x-card>
</x-app-layout>
