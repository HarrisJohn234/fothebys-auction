<x-app-layout>
    

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- LIVE LOT HIGHLIGHTS --}}
            <section class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Live lot highlights</h3>
                    <a href="{{ route('public.catalogue') }}"
                       class="text-sm text-gray-700 underline hover:text-gray-900">
                        See all lots
                    </a>
                </div>

                @if($liveLots->isEmpty())
                    <p class="text-sm text-gray-600">No live lots right now.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($liveLots as $lot)
                            <a href="{{ route('public.lots.show', $lot) }}"
                               class="border rounded-lg overflow-hidden hover:shadow transition bg-white">
                                <div class="aspect-[4/3] bg-gray-100">
                                    @if($lot->image_path)
                                        <img src="{{ asset('storage/'.$lot->image_path) }}"
                                             alt="Lot image"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="p-3">
                                    <div class="text-xs text-gray-500">
                                        Lot {{ $lot->lot_number }} • {{ $lot->category?->name }}
                                    </div>
                                    <div class="font-medium text-gray-900 line-clamp-1">
                                        {{ $lot->artist_name }}
                                    </div>
                                    <div class="text-sm text-gray-700 line-clamp-2">
                                        {{ $lot->subject_classification }}
                                    </div>
                                    <div class="text-sm text-gray-900 mt-2">
                                        Est: £{{ number_format($lot->estimate_low) }}–£{{ number_format($lot->estimate_high) }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- UPCOMING AUCTIONS --}}
            <section class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming auctions</h3>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.auctions.index') }}"
                               class="text-sm text-gray-700 underline hover:text-gray-900">
                                Manage auctions
                            </a>
                        @endif
                    @endauth
                </div>

                @if($upcomingAuctions->isEmpty())
                    <p class="text-sm text-gray-600">No upcoming auctions scheduled.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($upcomingAuctions as $auction)
                            <div class="border rounded-lg overflow-hidden bg-white">
                                <div class="aspect-[16/9] bg-gray-100">
                                    @if($auction->image_path)
                                        <img src="{{ asset('storage/'.$auction->image_path) }}"
                                             alt="Auction image"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="font-semibold text-gray-900 line-clamp-1">
                                        {{ $auction->title }}
                                    </div>
                                    <div class="text-sm text-gray-700 mt-1">
                                        Starts: {{ optional($auction->starts_at)->format('d M Y, H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2">
                                        Status: {{ $auction->status }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- LIVE AUCTIONS --}}
            <section class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Live auctions</h3>
                    <a href="{{ route('public.catalogue') }}"
                       class="text-sm text-gray-700 underline hover:text-gray-900">
                        View live lots
                    </a>
                </div>

                @if($liveAuctions->isEmpty())
                    <p class="text-sm text-gray-600">No live auctions right now.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($liveAuctions as $auction)
                            <div class="border rounded-lg overflow-hidden bg-white">
                                <div class="aspect-[16/9] bg-gray-100">
                                    @if($auction->image_path)
                                        <img src="{{ asset('storage/'.$auction->image_path) }}"
                                             alt="Auction image"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="font-semibold text-gray-900 line-clamp-1">
                                        {{ $auction->title }}
                                    </div>
                                    <div class="text-sm text-gray-700 mt-1">
                                        Started: {{ optional($auction->starts_at)->format('d M Y, H:i') }}
                                    </div>
                                    <div class="text-xs text-green-700 mt-2">
                                        Status: {{ $auction->status }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>
