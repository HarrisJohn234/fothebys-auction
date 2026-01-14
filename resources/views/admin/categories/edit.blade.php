@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Edit category</h1>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm mb-1">Name</label>
            <input name="name" class="border rounded p-2 w-full" value="{{ old('name', $category->name) }}" required>
        </div>

        <div>
            <label class="block text-sm mb-1">Slug</label>
            <input name="slug" class="border rounded p-2 w-full" value="{{ old('slug', $category->slug) }}" required>
        </div>

        <div class="flex gap-2">
            <button class="bg-black text-white rounded px-4 py-2">Save</button>
            <a class="border rounded px-4 py-2" href="{{ route('admin.categories.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
