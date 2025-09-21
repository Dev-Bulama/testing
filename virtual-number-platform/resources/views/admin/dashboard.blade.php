<x-app-layout>
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-card>
            <p class="text-sm text-slate-500">Total Numbers</p>
            <p class="mt-2 text-3xl font-semibold">{{ $numbersCount }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-slate-500">Active Rentals</p>
            <p class="mt-2 text-3xl font-semibold">{{ $activeRentals }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-slate-500">Customers</p>
            <p class="mt-2 text-3xl font-semibold">{{ $customersCount }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-slate-500">SMS Received</p>
            <p class="mt-2 text-3xl font-semibold">{{ $messagesCount }}</p>
        </x-card>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <x-card class="lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Latest Transactions</h2>
                <a href="{{ route('customer.invoices') }}" class="text-sm text-indigo-500">View all</a>
            </div>
            <table class="mt-4 w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        <th class="py-2">User</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestTransactions as $transaction)
                        <tr class="border-t border-slate-100">
                            <td class="py-2">{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td class="py-2">${{ number_format($transaction->amount, 2) }}</td>
                            <td class="py-2 capitalize">{{ $transaction->type }}</td>
                            <td class="py-2 capitalize">{{ $transaction->status }}</td>
                            <td class="py-2">{{ $transaction->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-slate-500">No transactions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
        <x-card>
            <h2 class="text-lg font-semibold mb-4">Admin Console</h2>
            <form method="POST" action="{{ route('admin.console.execute') }}" class="space-y-3">
                @csrf
                <x-label value="Command" />
                <x-input name="command" placeholder="status | check | terminate" />
                <x-button class="w-full">Run</x-button>
            </form>
            @if(session('console_output'))
                <div class="mt-4 rounded bg-slate-900 text-slate-200 px-3 py-2 text-sm font-mono">{{ session('console_output') }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
