<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Lot #{{ $lot->lot_number }}</h2>
    </x-slot>

    <div class="p-6 space-y-4">
        @if(session('status'))
            <div class="border rounded p-3 bg-gray-50">{{ session('status') }}</div>
        @endif

        <div class="border rounded p-4">
            <div class="text-sm text-gray-500">{{ $lot->category->name }}</div>
            <div class="font-semibold text-lg">{{ $lot->artist_name }} ({{ $lot->year_produced }})</div>
            <div>Subject: {{ $lot->subject_classification }}</div>
            <div class="mt-2">{{ $lot->description }}</div>
            <div class="mt-2">Estimate: £{{ $lot->estimate_low }}@if($lot->estimate_high)–£{{ $lot->estimate_high }}@endif</div>

            <div class="mt-4 text-sm text-gray-600">
                <div class="font-semibold">Category metadata</div>
                <pre class="bg-gray-50 p-2 rounded overflow-auto">{{ json_encode($lot->category_metadata, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>

        @auth
            @if(auth()->user()->role === 'client')
                <div class="border rounded p-4">
                    <div class="font-semibold mb-2">Submit commission bid</div>
                    <form method="POST" action="{{ route('client.bids.store', $lot) }}" class="flex gap-2 flex-wrap">
                        @csrf
                        <input class="border rounded p-2" name="max_bid_amount" placeholder="Max bid (£)">
                        <button class="bg-black text-white rounded px-4">Submit</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
