<x-app-layout>
    <div class="grid gap-6 lg:grid-cols-2">
        <x-card>
            <h2 class="text-lg font-semibold mb-4">Fund Wallet</h2>
            <form method="POST" action="{{ route('customer.wallet.fund') }}" class="space-y-4">
                @csrf
                @if ($errors->any())
                    <div class="rounded border border-red-500 bg-red-100 px-3 py-2 text-sm text-red-600">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div>
                    <x-label value="Amount" />
                    <x-input name="amount" type="number" min="1" step="1" value="10" />
                </div>
                <x-button>Fund with Stripe (demo)</x-button>
            </form>
        </x-card>
        <x-card>
            <h2 class="text-lg font-semibold mb-4">Balance</h2>
            <p class="text-3xl font-semibold">${{ number_format($balance, 2) }}</p>
        </x-card>
    </div>

    <x-card class="mt-8">
        <h2 class="text-lg font-semibold mb-4">Transactions</h2>
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
                @foreach($transactions as $transaction)
                    <tr class="border-t border-slate-100">
                        <td class="py-2">{{ $transaction->reference }}</td>
                        <td class="py-2">${{ number_format($transaction->amount, 2) }}</td>
                        <td class="py-2">{{ ucfirst($transaction->type) }}</td>
                        <td class="py-2">{{ ucfirst($transaction->status) }}</td>
                        <td class="py-2">{{ $transaction->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $transactions->links() }}</div>
    </x-card>
</x-app-layout>
