<x-app-layout>
    <x-card>
        <h1 class="text-xl font-semibold mb-4">Reports</h1>
        <form method="GET" class="flex flex-wrap gap-3 mb-6">
            <div>
                <x-label value="From" />
                <x-input name="from" type="date" value="{{ request('from', $from->toDateString()) }}" />
            </div>
            <div>
                <x-label value="To" />
                <x-input name="to" type="date" value="{{ request('to', $to->toDateString()) }}" />
            </div>
            <div class="self-end">
                <x-button>Filter</x-button>
            </div>
        </form>
        <div class="grid gap-4 md:grid-cols-3">
            <x-card>
                <p class="text-sm text-slate-500">Numbers rented</p>
                <p class="mt-2 text-2xl font-semibold">{{ $numbersRented }}</p>
            </x-card>
            <x-card>
                <p class="text-sm text-slate-500">Revenue</p>
                <p class="mt-2 text-2xl font-semibold">${{ number_format($revenue, 2) }}</p>
            </x-card>
            <x-card>
                <p class="text-sm text-slate-500">SMS traffic</p>
                <p class="mt-2 text-2xl font-semibold">{{ $smsCount }}</p>
            </x-card>
        </div>
    </x-card>
</x-app-layout>
