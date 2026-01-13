<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lot {{ $lot->lot_number }}
            </h2>

            <div class="flex items-center gap-3">
                @if (Route::has('admin.lots.edit'))
                <a href="{{ route('admin.lots.edit', $lot) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700">
                    Edit
                </a>
                @endif

                <a href="{{ route('admin.lots.index') }}" class="text-sm underline text-gray-700">
                    Back to lots
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div><span class="font-semibold">Artist:</span> {{ $lot->artist_name }}</div>
                    <div><span class="font-semibold">Year:</span> {{ $lot->year_produced }}</div>
                    <div><span class="font-semibold">Subject:</span> {{ $lot->subject_classification }}</div>
                    <div><span class="font-semibold">Status:</span> {{ $lot->status }}</div>
                    <div><span class="font-semibold">Category:</span> {{ $lot->category?->name ?? '—' }}</div>
                    <div><span class="font-semibold">Auction date:</span> {{ $lot->auction_date?->format('Y-m-d') ?? '—' }}</div>
                    <div><span class="font-semibold">Estimate low:</span> {{ number_format($lot->estimate_low, 0) }}</div>
                    <div><span class="font-semibold">Estimate high:</span> {{ $lot->estimate_high !== null ? number_format($lot->estimate_high, 0) : '—' }}</div>
                </div>

                <div class="mt-6">
                    <div class="font-semibold mb-1">Description</div>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $lot->description }}</div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="font-semibold mb-3">Category metadata</div>

                @php($meta = is_array($lot->category_metadata) ? $lot->category_metadata : [])

                @if (empty($meta))
                <div class="text-sm text-gray-600">No metadata.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left border-b">
                            <tr>
                                <th class="py-2 pr-4">Key</th>
                                <th class="py-2 pr-4">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meta as $k => $v)
                            <tr class="border-b">
                                <td class="py-2 pr-4 font-mono">{{ $k }}</td>
                                <td class="py-2 pr-4">
                                    @if (is_array($v))
                                    <pre class="text-xs">{{ json_encode($v, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                    {{ (string) $v }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="font-semibold mb-3">Bids</div>

                @php($bidsList = isset($bids) ? $bids : ($lot->relationLoaded('bids') ? $lot->bids : $lot->bids()->get()))

                @if ($bidsList->count() === 0)
                <div class="text-sm text-gray-600">No bids yet.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left border-b">
                            <tr>
                                <th class="py-2 pr-4">Bidder</th>
                                <th class="py-2 pr-4">Amount</th>
                                <th class="py-2 pr-4">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bidsList as $bid)
                            <tr class="border-b">
                                <td class="py-2 pr-4">{{ $bid->user?->name ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ number_format($bid->amount, 2) }}</td>
                                <td class="py-2 pr-4">{{ $bid->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>