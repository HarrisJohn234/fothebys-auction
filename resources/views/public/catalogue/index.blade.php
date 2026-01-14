<x-app-layout>

    <div class="p-6 space-y-4">
        <form class="flex gap-2 flex-wrap items-end" method="GET" action="{{ route('public.catalogue') }}">
            <div class="flex flex-col">
                <label class="text-xs text-gray-500">Keyword</label>
                <input class="border rounded p-2" name="q" placeholder="Search artist, subject, lot no, description" value="{{ request('q') }}">
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500">Category</label>
                <select class="border rounded p-2" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->slug }}" @selected(request('category')===$c->slug)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500">Auction</label>
                <select class="border rounded p-2" name="auction_id">
                    <option value="">All auctions</option>
                    @foreach($auctions as $a)
                        <option value="{{ $a->id }}" @selected((string)request('auction_id') === (string)$a->id)>
                            {{ $a->title }} ({{ optional($a->starts_at)->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500">Auction from</label>
                <input type="date" class="border rounded p-2" name="auction_from" value="{{ request('auction_from') }}">
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500">Auction to</label>
                <input type="date" class="border rounded p-2" name="auction_to" value="{{ request('auction_to') }}">
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500">Min £</label>
                <input type="number" class="border rounded p-2 w-28" name="min" placeholder="Min" value="{{ request('min') }}">
            </div>

            <div class="flex flex-col">
                <label class="text-xs text-gray-500">Max £</label>
                <input type="number" class="border rounded p-2 w-28" name="max" placeholder="Max" value="{{ request('max') }}">
            </div>

            <button class="bg-black text-white rounded px-4 py-2">Search</button>

            <a class="border rounded px-4 py-2" href="{{ route('public.catalogue') }}">Reset</a>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($lots as $lot)
                <a class="border rounded p-4 hover:shadow flex gap-4 items-stretch" href="{{ route('public.lots.show', $lot) }}">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm text-gray-500">
                            {{ $lot->category->name }}
                            @if($lot->auction)
                                • {{ $lot->auction->title }}
                            @endif
                        </div>

                        <div class="font-semibold truncate">{{ $lot->artist_name }} ({{ $lot->year_produced }})</div>
                        <div class="text-sm">Lot #{{ $lot->lot_number }}</div>
                        <div class="text-sm">
                            Estimate: £{{ $lot->estimate_low }}@if($lot->estimate_high)–£{{ $lot->estimate_high }}@endif
                        </div>

                        @if($lot->auction?->starts_at)
                            <div class="text-xs text-gray-500 mt-1">
                                Auction date: {{ $lot->auction->starts_at->format('d M Y') }}
                            </div>
                        @endif
                    </div>

                    <div class="w-24 h-24 md:w-28 md:h-28 flex-shrink-0 border rounded bg-gray-50 overflow-hidden">
                        @if($lot->image_url)
                            <img src="{{ $lot->image_url }}" alt="Lot image" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[10px] text-gray-400">
                                No image
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        {{ $lots->links() }}
    </div>
</x-app-layout>
