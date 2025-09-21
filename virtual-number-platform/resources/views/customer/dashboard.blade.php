<x-app-layout>
    <div class="grid gap-6 md:grid-cols-2">
        <x-card>
            <p class="text-sm text-slate-500">Wallet Balance</p>
            <p class="mt-2 text-3xl font-semibold">${{ number_format($balance, 2) }}</p>
            <a href="{{ route('customer.wallet') }}" class="mt-4 inline-flex text-sm text-indigo-500">Manage wallet →</a>
        </x-card>
        <x-card>
            <p class="text-sm text-slate-500">Active Numbers</p>
            <p class="mt-2 text-3xl font-semibold">{{ $numbers->count() }}</p>
            <a href="{{ route('customer.numbers.index') }}" class="mt-4 inline-flex text-sm text-indigo-500">View numbers →</a>
        </x-card>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-card>
            <h2 class="text-lg font-semibold mb-4">Recent Messages</h2>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($numbers as $number)
                    @foreach($number->messages->take(3) as $message)
                        <div class="rounded border border-slate-200 px-3 py-2">
                            <p class="text-sm text-slate-500">{{ $number->number }} • {{ $message->received_at->diffForHumans() }}</p>
                            <p class="text-sm">{{ $message->body }}</p>
                        </div>
                    @endforeach
                @endforeach
                @if($numbers->sum(fn($n) => $n->messages->count()) === 0)
                    <p class="text-sm text-slate-500">No messages yet.</p>
                @endif
            </div>
        </x-card>
        <x-card>
            <h2 class="text-lg font-semibold mb-4">Recent Transactions</h2>
            <ul class="space-y-3 text-sm">
                @forelse($transactions as $transaction)
                    <li class="flex justify-between">
                        <span>{{ $transaction->created_at->format('M d, Y') }}</span>
                        <span class="font-semibold">${{ number_format($transaction->amount, 2) }}</span>
                    </li>
                @empty
                    <li class="text-slate-500">No transactions found.</li>
                @endforelse
            </ul>
        </x-card>
    </div>
</x-app-layout>
