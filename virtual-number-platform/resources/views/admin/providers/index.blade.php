<x-app-layout>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">API Providers</h1>
        <a href="{{ route('admin.providers.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white">Add Provider</a>
    </div>
    <x-card>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500">
                    <th class="py-2">Name</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Created</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($providers as $provider)
                    <tr class="border-t border-slate-100">
                        <td class="py-2">{{ $provider->name }}</td>
                        <td class="py-2">{{ $provider->status ? 'Active' : 'Inactive' }}</td>
                        <td class="py-2">{{ $provider->created_at->diffForHumans() }}</td>
                        <td class="py-2 text-right space-x-2">
                            <a href="{{ route('admin.providers.edit', $provider) }}" class="text-indigo-600">Edit</a>
                            <form method="POST" action="{{ route('admin.providers.destroy', $provider) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $providers->links() }}</div>
    </x-card>
</x-app-layout>
