@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Lots</h1>
        <a href="{{ route('admin.lots.create') }}" class="border rounded px-4 py-2">Create lot</a>
    </div>

    @if (session('success'))
        <div class="mb-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
        <input name="q" value="{{ request('q') }}" class="border rounded p-2" placeholder="Search (artist/subject/desc/lot#)">

        <select name="category_id" class="border rounded p-2">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string)$category->id === (string)request('category_id'))>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <input name="min_estimate" value="{{ request('min_estimate') }}" class="border rounded p-2" placeholder="Min estimate">

        <input name="max_estimate" value="{{ request('max_estimate') }}" class="border rounded p-2" placeholder="Max estimate">

        <div class="flex gap-2">
            <button class="border rounded px-4 py-2">Filter</button>
            <a href="{{ route('admin.lots.index') }}" class="border rounded px-4 py-2">Reset</a>
        </div>
    </form>

    <div class="border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="p-3">Lot #</th>
                    <th class="p-3">Artist</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Est.</th>
                    <th class="p-3">Status</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($lots as $lot)
                    <tr class="border-t">
                        <td class="p-3 font-mono">{{ $lot->lot_number }}</td>
                        <td class="p-3">{{ $lot->artist_name }}</td>
                        <td class="p-3">{{ $lot->category?->name }}</td>
                        <td class="p-3">
                            £{{ number_format((float)$lot->estimate_low, 2) }}–£{{ number_format((float)$lot->estimate_high, 2) }}
                        </td>
                        <td class="p-3">{{ $lot->status }}</td>
                        <td class="p-3 text-right">
                            <a class="underline" href="{{ route('admin.lots.show', $lot) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-3" colspan="6">No lots found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $lots->links() }}
    </div>
</div>
@endsection
