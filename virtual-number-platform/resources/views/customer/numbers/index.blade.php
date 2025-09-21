<x-app-layout>
    @if ($errors->any())
        <div class="mb-4 rounded border border-red-500 bg-red-100 px-3 py-2 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif
    <div class="grid gap-6 lg:grid-cols-2">
        <x-card>
            <h2 class="text-lg font-semibold mb-4">Available Numbers</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($availableNumbers as $number)
                    <div class="rounded border border-slate-200 px-4 py-3">
                        <p class="text-sm font-semibold">{{ $number->number }} <span class="text-slate-500">({{ $number->country }})</span></p>
                        <p class="text-xs text-slate-500">Provider: {{ $number->provider->name ?? 'N/A' }} • Cost: ${{ number_format($number->cost, 2) }}</p>
                        <form method="POST" action="{{ route('customer.numbers.rent', $number) }}" class="mt-2">
                            @csrf
                            <x-button>Rent</x-button>
                        </form>
                    </div>
                @endforeach
                @if($availableNumbers->isEmpty())
                    <p class="text-sm text-slate-500">No numbers available right now. Check back soon.</p>
                @endif
            </div>
        </x-card>
        <x-card>
            <h2 class="text-lg font-semibold mb-4">Your Numbers</h2>
            <div class="space-y-4">
                @foreach($activeNumbers as $number)
                    <div class="rounded border border-slate-200 px-4 py-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-semibold">{{ $number->number }}</p>
                                <p class="text-xs text-slate-500">Expires {{ optional($number->expires_at)->diffForHumans() ?? 'n/a' }}</p>
                            </div>
                            <form method="POST" action="{{ route('customer.numbers.release', $number) }}">
                                @csrf
                                <x-button class="bg-red-600 hover:bg-red-700">Release</x-button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('customer.numbers.extend', $number) }}" class="mt-3 flex items-center gap-2">
                            @csrf
                            <select name="days" class="rounded border border-slate-300 px-2 py-1 text-sm">
                                <option value="7">Extend 7 days</option>
                                <option value="30">Extend 30 days</option>
                            </select>
                            <x-button class="text-sm">Extend</x-button>
                        </form>
                        <div class="mt-3 bg-slate-100 rounded px-3 py-2 text-xs">
                            <p class="font-semibold">Messages</p>
                            @forelse($number->messages()->latest()->take(5)->get() as $message)
                                <p class="mt-1">{{ $message->received_at->format('d M H:i') }} • {{ $message->body }}</p>
                            @empty
                                <p class="text-slate-500">No messages yet.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
                @if($activeNumbers->isEmpty())
                    <p class="text-sm text-slate-500">You have no active numbers.</p>
                @endif
            </div>
        </x-card>
    </div>
</x-app-layout>
