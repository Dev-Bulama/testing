<x-app-layout>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Phone Numbers</h1>
        <div class="space-x-3">
            <form method="POST" action="{{ route('admin.numbers.fetch') }}" class="inline-flex items-center gap-2">
                @csrf
                <select name="provider_id" class="rounded border border-slate-300 px-3 py-2">
                    @foreach($providers as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                    @endforeach
                </select>
                <x-button>Sync from provider</x-button>
            </form>
            <a href="{{ route('admin.numbers.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white">Add Manual</a>
        </div>
    </div>
    <x-card>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500">
                    <th class="py-2">Number</th>
                    <th class="py-2">Provider</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Cost</th>
                    <th class="py-2">Rented By</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($numbers as $number)
                    <tr class="border-t border-slate-100">
                        <td class="py-2">{{ $number->number }}</td>
                        <td class="py-2">{{ $number->provider->name ?? '—' }}</td>
                        <td class="py-2 capitalize">{{ $number->status }}</td>
                        <td class="py-2">${{ number_format($number->cost, 2) }}</td>
                        <td class="py-2">{{ $number->customer->name ?? '—' }}</td>
                        <td class="py-2 text-right space-x-2">
                            <a class="text-indigo-600" href="{{ route('admin.numbers.edit', $number) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.numbers.destroy', $number) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $numbers->links() }}</div>
    </x-card>
</x-app-layout>
