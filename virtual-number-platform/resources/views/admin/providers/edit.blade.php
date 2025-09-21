<x-app-layout>
    <x-card>
        <h1 class="text-xl font-semibold mb-4">Edit Provider</h1>
        <form method="POST" action="{{ route('admin.providers.update', $provider) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <x-label value="Name" />
                <x-input name="name" value="{{ old('name', $provider->name) }}" required />
            </div>
            <div>
                <x-label value="API Key" />
                <x-input name="api_key" value="{{ old('api_key', $provider->api_key) }}" />
            </div>
            <div>
                <x-label value="API Secret" />
                <x-input name="api_secret" value="{{ old('api_secret', $provider->api_secret) }}" />
            </div>
            <div>
                <x-label value="Status" />
                <select name="status" class="mt-1 block w-full rounded border border-slate-300 px-3 py-2">
                    <option value="1" @selected($provider->status)>Active</option>
                    <option value="0" @selected(! $provider->status)>Inactive</option>
                </select>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <x-label value="Default Country" />
                    <x-input name="configuration[country]" value="{{ data_get($provider->configuration, 'country', 'US') }}" />
                </div>
                <div>
                    <x-label value="Default Cost" />
                    <x-input name="configuration[default_cost]" type="number" step="0.01" value="{{ data_get($provider->configuration, 'default_cost', 5) }}" />
                </div>
            </div>
            <x-button>Update</x-button>
        </form>
    </x-card>
</x-app-layout>
