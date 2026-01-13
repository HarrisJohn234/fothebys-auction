@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $auction->title }}</h1>
            <div class="text-sm text-gray-600">
                {{ optional($auction->starts_at)->format('Y-m-d H:i') }}
                → {{ optional($auction->ends_at)->format('Y-m-d H:i') }}
                • {{ $auction->status }}
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.auctions.edit', $auction) }}" class="border rounded px-4 py-2">Edit</a>

            <form method="POST" action="{{ route('admin.auctions.destroy', $auction) }}"
                  onsubmit="return confirm('Delete this auction? Lots will be unassigned.');">
                @csrf
                @method('DELETE')
                <button class="border rounded px-4 py-2">Delete</button>
            </form>
        </div>
    </div>

    <div class="border rounded p-4">
        <h2 class="text-lg font-semibold mb-3">Assigned lots</h2>

        @if($auction->lots->isEmpty())
            <div class="text-sm text-gray-600">No lots assigned.</div>
        @else
            <div class="border rounded overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">Lot #</th>
                            <th class="p-3 text-left">Artist</th>
                            <th class="p-3 text-left">Category</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auction->lots as $lot)
                            <tr class="border-t">
                                <td class="p-3 font-mono">{{ $lot->lot_number }}</td>
                                <td class="p-3">{{ $lot->artist_name }}</td>
                                <td class="p-3">{{ $lot->category?->name }}</td>
                                <td class="p-3">{{ $lot->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.auctions.index') }}" class="underline">Back to auctions</a>
    </div>
</div>
@endsection
