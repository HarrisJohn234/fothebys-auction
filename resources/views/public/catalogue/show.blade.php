<x-app-layout>
    

    <div class="p-6 space-y-4">
        @if (session('success'))
            <div class="border rounded p-3 bg-gray-50">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="border rounded p-3 bg-red-50 text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif
        <h2 class="font-semibold text-xl">Lot #{{ $lot->lot_number }}</h2>
        <div class="border rounded p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <div class="text-sm text-gray-500">{{ $lot->category->name }}</div>
                    <div class="font-semibold text-lg">{{ $lot->artist_name }} ({{ $lot->year_produced }})</div>
                    <div>Subject: {{ $lot->subject_classification }}</div>
                    <div class="mt-2">{{ $lot->description }}</div>
                    <div class="mt-2">
                        Estimate: £{{ $lot->estimate_low }}@if($lot->estimate_high)–£{{ $lot->estimate_high }}@endif
                    </div>
                </div>

                <div>
                    <div class="w-full aspect-square border rounded bg-gray-50 overflow-hidden">
                        @if($lot->image_url)
                            <img src="{{ $lot->image_url }}" alt="Lot image" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                No image
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <div class="font-semibold">Category metadata</div>
                <pre class="bg-gray-50 p-2 rounded overflow-auto">{{ json_encode($lot->category_metadata, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>

        @auth
            <div class="border rounded p-4">
                <div class="font-semibold mb-2">Place a Commission Bid</div>

                <form method="POST" action="{{ route('lots.commission-bid', $lot) }}" class="flex gap-2 flex-wrap items-end">
                    @csrf

                    <div>
                        <label class="block text-sm mb-1" for="max_bid_amount">Maximum bid amount</label>
                        <input
                            id="max_bid_amount"
                            name="max_bid_amount"
                            type="number"
                            step="1"
                            min="1"
                            value="{{ old('max_bid_amount') }}"
                            class="border rounded p-2"
                            placeholder="Max bid (£)"
                            required
                        >
                    </div>

                    <button type="submit" class="bg-black text-white rounded px-4 py-2">
                        Submit
                    </button>
                </form>

                <p class="text-xs text-gray-500 mt-2">
                    Submitting again will update your existing commission bid for this lot.
                </p>
            </div>
        @endauth

        @guest
            <div class="mt-6 text-sm text-gray-600">
                Please log in to place a commission bid.
            </div>
        @endguest
    </div>
</x-app-layout>
