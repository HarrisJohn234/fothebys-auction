@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="border rounded px-4 py-2">Create category</a>
    </div>

    @if (session('success'))
        <div class="mb-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="border rounded overflow-hidden">
        <table class="w-full text-sm table-fixed border-collapse">
            <colgroup>
                <col class="w-1/2">
                <col class="w-1/3">
                <col class="w-1/6">
            </colgroup>
            <thead class="bg-gray-50">
                <tr>
                    <th class="!p-3 !text-left font-semibold">Name</th>
                    <th class="!p-3 !text-left font-semibold">Slug</th>
                    <th class="!p-3 !text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border-t">
                        <td class="!p-3">{{ $category->name }}</td>
                        <td class="!p-3 font-mono text-xs">{{ $category->slug }}</td>
                        <td class="!p-3 text-center">
                            <a class="underline mr-3" href="{{ route('admin.categories.edit', $category) }}">Edit</a>

                            <form class="inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Archive this category? Lots will keep their historic category reference.');">
                                @csrf
                                @method('DELETE')
                                <button class="underline text-red-600">Archive</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
