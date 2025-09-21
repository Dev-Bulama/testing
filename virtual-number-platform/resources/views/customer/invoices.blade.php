<x-app-layout>
    <x-card>
        <h1 class="text-xl font-semibold mb-4">Invoice History</h1>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500">
                    <th class="py-2">Reference</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Type</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr class="border-t border-slate-100">
                        <td class="py-2">{{ $transaction->reference }}</td>
                        <td class="py-2">${{ number_format($transaction->amount, 2) }}</td>
                        <td class="py-2">{{ ucfirst($transaction->type) }}</td>
                        <td class="py-2">{{ ucfirst($transaction->status) }}</td>
                        <td class="py-2">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-slate-500">No invoices yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
</x-app-layout>
