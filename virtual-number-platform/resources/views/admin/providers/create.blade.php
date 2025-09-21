<x-app-layout>
    <x-card>
        <h1 class="text-xl font-semibold mb-4">Add Provider</h1>
        <form method="POST" action="{{ route('admin.providers.store') }}" class="space-y-4">
            @csrf
            <div>
                <x-label value="Name" />
                <x-input name="name" value="{{ old('name') }}" required />
            </div>
            <div>
                <x-label value="API Key" />
                <x-input name="api_key" value="{{ old('api_key') }}" />
            </div>
            <div>
                <x-label value="API Secret" />
                <x-input name="api_secret" value="{{ old('api_secret') }}" />
            </div>
            <div>
                <x-label value="Status" />
                <select name="status" class="mt-1 block w-full rounded border border-slate-300 px-3 py-2">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-label value="Default Country" />
                    <x-input name="configuration[country]" value="US" />
                </div>
                <div>
                    <x-label value="Default Cost" />
                    <x-input name="configuration[default_cost]" type="number" step="0.01" value="5" />
                </div>
            </div>
            <x-button>Save</x-button>
        </form>
    </x-card>
</x-app-layout>
