@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Auctions</h1>
        <a href="{{ route('admin.auctions.create') }}" class="border rounded px-4 py-2">Create auction</a>
    </div>

    @if (session('success'))
        <div class="mb-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-6 flex gap-2 flex-wrap">
        <input name="q" value="{{ request('q') }}" class="border rounded p-2" placeholder="Search title">
        <input name="status" value="{{ request('status') }}" class="border rounded p-2" placeholder="Status (e.g. DRAFT/LIVE/CLOSED)">
        <button class="border rounded px-4 py-2">Filter</button>
        <a href="{{ route('admin.auctions.index') }}" class="border rounded px-4 py-2">Reset</a>
    </form>

    <div class="border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Title</th>
                    <th class="p-3 text-left">Starts</th>
                    <th class="p-3 text-left">Ends</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auctions as $auction)
                    <tr class="border-t">
                        <td class="p-3">{{ $auction->title }}</td>
                        <td class="p-3">{{ optional($auction->starts_at)->format('Y-m-d H:i') }}</td>
                        <td class="p-3">{{ optional($auction->ends_at)->format('Y-m-d H:i') }}</td>
                        <td class="p-3">{{ $auction->status }}</td>
                        <td class="p-3 text-right">
                            <a class="underline mr-3" href="{{ route('admin.auctions.show', $auction) }}">View</a>
                            <a class="underline" href="{{ route('admin.auctions.edit', $auction) }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-3" colspan="5">No auctions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $auctions->links() }}
    </div>
</div>
@endsection
