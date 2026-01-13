<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Lot
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.lots.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input name="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300" />
                        @error('title') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
                        @error('description') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reserve Price (optional)</label>
                            <input type="number" step="0.01" name="reserve_price" value="{{ old('reserve_price') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300" />
                            @error('reserve_price') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Starting Bid (optional)</label>
                            <input type="number" step="0.01" name="starting_bid" value="{{ old('starting_bid') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300" />
                            @error('starting_bid') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700">
                            Save Lot
                        </button>

                        <a href="{{ route('admin.lots.index') }}" class="text-sm underline text-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
