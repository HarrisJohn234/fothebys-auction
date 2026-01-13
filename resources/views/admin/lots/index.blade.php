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
        <table class="w-full text-sm table-fixed border-collapse">
            <colgroup>
                <col class="w-32">
                <col class="w-48">
                <col class="w-48">
                <col class="w-56">
                <col class="w-40">
                <col class="w-28">
            </colgroup>

            <thead class="bg-gray-50">
                <tr>
                    <th class="!p-3 !text-center !align-middle font-semibold">Lot #</th>
                    <th class="!p-3 !text-center !align-middle font-semibold">Artist</th>
                    <th class="!p-3 !text-center !align-middle font-semibold">Category</th>
                    <th class="!p-3 !text-center !align-middle font-semibold">Estimate</th>
                    <th class="!p-3 !text-center !align-middle font-semibold">Status</th>
                    <th class="!p-3 !text-center !align-middle font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($lots as $lot)
                    <tr class="border-t">
                        <td class="!p-3 !text-center !align-middle font-mono">{{ $lot->lot_number }}</td>
                        <td class="!p-3 !text-center !align-middle">{{ $lot->artist_name }}</td>
                        <td class="!p-3 !text-center !align-middle">{{ $lot->category?->name }}</td>
                        <td class="!p-3 !text-center !align-middle">
                            £{{ number_format($lot->estimate_low) }}
                            @if($lot->estimate_high)
                                –£{{ number_format($lot->estimate_high) }}
                            @endif
                        </td>
                        <td class="!p-3 !text-center !align-middle">{{ $lot->status }}</td>
                        <td class="!p-3 !text-center !align-middle">
                            <a href="{{ route('admin.lots.show', $lot) }}" class="underline">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="mt-4">
        {{ $lots->links() }}
    </div>
</div>
@endsection
