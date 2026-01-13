@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Lot {{ $lot->lot_number }}</h1>
            <p class="text-sm text-gray-600">{{ $lot->artist_name }} • {{ $lot->category?->name }}</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.lots.edit', $lot) }}" class="border rounded px-4 py-2">Edit</a>

            <form method="POST" action="{{ route('admin.lots.destroy', $lot) }}" onsubmit="return confirm('Archive this lot?');">
                @csrf
                @method('DELETE')
                <button class="border rounded px-4 py-2">Archive</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="border rounded p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div><span class="text-gray-500">Year:</span> {{ $lot->year_produced }}</div>
            <div><span class="text-gray-500">Subject:</span> {{ $lot->subject_classification }}</div>
            <div><span class="text-gray-500">Status:</span> {{ $lot->status }}</div>
            <div><span class="text-gray-500">Estimate:</span> £{{ number_format((float)$lot->estimate_low, 2) }}–£{{ number_format((float)$lot->estimate_high, 2) }}</div>
            <div><span class="text-gray-500">Auction:</span> {{ $lot->auction?->title ?? 'Not assigned' }}</div>
        </div>

        <div class="mt-4">
            <div class="text-gray-500 text-sm mb-1">Description</div>
            <div class="text-sm whitespace-pre-wrap">{{ $lot->description }}</div>
        </div>

        <div class="mt-4">
            <div class="text-gray-500 text-sm mb-1">Category metadata</div>
            <pre class="text-xs bg-gray-50 border rounded p-3 overflow-auto">{{ json_encode($lot->category_metadata, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>

    <div class="border rounded p-4">
        <h2 class="text-lg font-semibold mb-3">Commission bids</h2>

        @if ($bids->isEmpty())
            <p class="text-sm text-gray-600">No bids for this lot.</p>
        @else
            <div class="overflow-hidden border rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            <th class="p-3">Bidder</th>
                            <th class="p-3">Max bid</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Placed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bids as $bid)
                            <tr class="border-t">
                                <td class="p-3">{{ $bid->user?->name ?? 'Unknown' }}</td>
                                <td class="p-3">£{{ number_format((float)$bid->max_bid_amount, 2) }}</td>
                                <td class="p-3">{{ $bid->status }}</td>
                                <td class="p-3">{{ optional($bid->created_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
