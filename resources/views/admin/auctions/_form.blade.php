@csrf

@if ($errors->any())
    <div class="mb-4 text-sm text-red-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm mb-1">Title</label>
        <input name="title" class="w-full border rounded p-2"
               value="{{ old('title', $auction->title ?? '') }}" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Status</label>
        <input name="status" class="w-full border rounded p-2"
               value="{{ old('status', $auction->status ?? 'DRAFT') }}" required>
        <p class="text-xs text-gray-500 mt-1">Example: DRAFT, LIVE, CLOSED</p>
    </div>

    <div>
        <label class="block text-sm mb-1">Starts at</label>
        <input type="datetime-local" name="starts_at" class="w-full border rounded p-2"
               value="{{ old('starts_at', isset($auction) && $auction->starts_at ? $auction->starts_at->format('Y-m-d\TH:i') : '') }}" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Ends at</label>
        <input type="datetime-local" name="ends_at" class="w-full border rounded p-2"
               value="{{ old('ends_at', isset($auction) && $auction->ends_at ? $auction->ends_at->format('Y-m-d\TH:i') : '') }}" required>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm mb-2 font-semibold">Assign lots to this auction</label>

    <div class="border rounded p-3 max-h-72 overflow-auto">
        @forelse($lots as $lot)
            @php
                $checked = in_array($lot->id, old('lots', $selectedLotIds ?? []));
            @endphp
            <label class="flex items-center gap-2 py-1">
                <input type="checkbox" name="lots[]" value="{{ $lot->id }}" @checked($checked)>
                <span class="text-sm">
                    <span class="font-mono">#{{ $lot->lot_number }}</span>
                    — {{ $lot->artist_name }}
                    <span class="text-gray-500">({{ $lot->category?->name }})</span>
                </span>
            </label>
        @empty
            <div class="text-sm text-gray-600">No eligible lots available.</div>
        @endforelse
    </div>
</div>

<div class="mt-6 flex gap-2">
    <button class="border rounded px-4 py-2">Save</button>
    <a href="{{ route('admin.auctions.index') }}" class="border rounded px-4 py-2">Cancel</a>
</div>
