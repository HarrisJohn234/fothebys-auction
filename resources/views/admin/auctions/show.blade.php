@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">
            Auction: {{ $auction->title }}
        </h1>

        <a href="{{ route('admin.auctions.index') }}" class="underline">
            Back to auctions
        </a>
    </div>

    {{-- Status + timing --}}
    <div class="border rounded p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <div class="text-gray-500">Status</div>
                <div class="font-semibold">{{ $auction->status }}</div>
            </div>

            <div>
                <div class="text-gray-500">Starts at</div>
                <div>
                    {{ $auction->starts_at?->format('Y-m-d H:i') ?? '—' }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">Ends at</div>
                <div>
                    {{ $auction->ends_at?->format('Y-m-d H:i') ?? '—' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="border border-green-200 bg-green-50 text-green-800 p-3 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Actions --}}
    <div class="flex gap-3">
        <a href="{{ route('admin.auctions.edit', $auction) }}"
           class="border rounded px-4 py-2">
            Edit auction
        </a>

        @if($auction->status === 'LIVE')
            <form method="POST" action="{{ route('admin.auctions.close', $auction) }}">
                @csrf
                <button
                    type="submit"
                    class="border rounded px-4 py-2 bg-red-50 hover:bg-red-100"
                    onclick="return confirm('Close this auction and generate sales? This cannot be undone.')"
                >
                    Close auction
                </button>
            </form>
        @endif
    </div>

    {{-- Lots in this auction --}}
    <div class="border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Lot #</th>
                    <th class="p-3 text-left">Artist</th>
                    <th class="p-3 text-left">Category</th>
                    <th class="p-3 text-right">Estimate</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auction->lots as $lot)
                    <tr class="border-t">
                        <td class="p-3 font-mono">
                            {{ $lot->lot_number }}
                        </td>
                        <td class="p-3">
                            {{ $lot->artist_name }}
                        </td>
                        <td class="p-3">
                            {{ $lot->category?->name ?? '—' }}
                        </td>
                        <td class="p-3 text-right">
                            £{{ number_format($lot->estimate_low) }}
                            @if($lot->estimate_high)
                                –£{{ number_format($lot->estimate_high) }}
                            @endif
                        </td>
                        <td class="p-3">
                            {{ $lot->status }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-3 text-center text-gray-500" colspan="5">
                            No lots assigned to this auction.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
