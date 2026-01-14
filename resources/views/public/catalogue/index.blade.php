<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Public Catalogue</h2>
    </x-slot>

    <div class="p-6 space-y-4">
        <form class="flex gap-2 flex-wrap" method="GET" action="{{ route('public.catalogue') }}">
            <input class="border rounded p-2" name="q" placeholder="Search artist, subject, lot no, description" value="{{ request('q') }}">
            <select class="border rounded p-2" name="category">
                <option value="">All categories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->slug }}" @selected(request('category')===$c->slug)>{{ $c->name }}</option>
                @endforeach
            </select>
            <input class="border rounded p-2 w-28" name="min" placeholder="Min £" value="{{ request('min') }}">
            <input class="border rounded p-2 w-28" name="max" placeholder="Max £" value="{{ request('max') }}">
            <button class="bg-black text-white rounded px-4">Search</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($lots as $lot)
                <a class="border rounded p-4 hover:shadow flex gap-4 items-stretch" href="{{ route('public.lots.show', $lot) }}">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm text-gray-500">{{ $lot->category->name }}</div>
                        <div class="font-semibold truncate">{{ $lot->artist_name }} ({{ $lot->year_produced }})</div>
                        <div class="text-sm">Lot #{{ $lot->lot_number }}</div>
                        <div class="text-sm">
                            Estimate: £{{ $lot->estimate_low }}@if($lot->estimate_high)–£{{ $lot->estimate_high }}@endif
                        </div>
                    </div>

                    {{-- Reserved image slot: keeps card size consistent even if no image --}}
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
