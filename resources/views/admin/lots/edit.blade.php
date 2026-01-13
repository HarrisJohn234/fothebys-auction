<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Lot {{ $lot->lot_number }}
            </h2>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.lots.show', $lot) }}" class="text-sm underline text-gray-700">View</a>
                <a href="{{ route('admin.lots.index') }}" class="text-sm underline text-gray-700">Back to lots</a>
            </div>
        </div>
    </x-slot>

    @php
        $meta = is_array($lot->category_metadata) ? $lot->category_metadata : [];
        $selectedCategoryId = old('category_id', $lot->category_id);
        $selectedStatus = old('status', $lot->status);
    @endphp

    <div class="py-12" x-data="lotEditForm()" x-init="init()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                @if ($errors->any())
                    <div class="p-4 rounded border border-red-200 bg-red-50 text-red-800 text-sm">
                        <div class="font-semibold mb-2">Please fix the following:</div>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.lots.update', $lot) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Artist name *</label>
                            <input name="artist_name" value="{{ old('artist_name', $lot->artist_name) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300" />
                            @error('artist_name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Year produced *</label>
                            <input type="number" name="year_produced" value="{{ old('year_produced', $lot->year_produced) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300" />
                            @error('year_produced') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subject classification *</label>
                        <input name="subject_classification" value="{{ old('subject_classification', $lot->subject_classification) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300" />
                        @error('subject_classification') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category *</label>
                        <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300"
                                @change="setCategoryFromSelect()">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        data-slug="{{ $category->slug }}"
                                        @selected($selectedCategoryId == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror

                        <p class="text-xs text-gray-500 mt-1">
                            Changing category may require updating category-specific metadata fields below.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" required class="mt-1 block w-full rounded-md border-gray-300">
                            @foreach(['PENDING','LIVE','ARCHIVED','WITHDRAWN'] as $status)
                                <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300">{{ old('description', $lot->description) }}</textarea>
                        @error('description') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estimate low *</label>
                            <input type="number" name="estimate_low" value="{{ old('estimate_low', $lot->estimate_low) }}" required min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300" />
                            @error('estimate_low') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estimate high (optional)</label>
                            <input type="number" name="estimate_high" value="{{ old('estimate_high', $lot->estimate_high) }}" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300" />
                            @error('estimate_high') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Auction date (optional)</label>
                        <input type="date" name="auction_date"
                               value="{{ old('auction_date', optional($lot->auction_date)->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300" />
                        @error('auction_date') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    {{-- CATEGORY METADATA --}}
                    <div class="border-t pt-4">
                        <div class="font-semibold mb-2">Category-specific details *</div>

                        {{-- drawings + paintings --}}
                        <template x-if="slug === 'drawings' || slug === 'paintings'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Medium *</label>
                                    <input name="category_metadata[medium]"
                                           value="{{ old('category_metadata.medium', $meta['medium'] ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300" required>
                                    @error('category_metadata.medium') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Height (cm) *</label>
                                        <input type="number" step="0.01" name="category_metadata[height_cm]"
                                               value="{{ old('category_metadata.height_cm', $meta['height_cm'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.height_cm') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Length (cm) *</label>
                                        <input type="number" step="0.01" name="category_metadata[length_cm]"
                                               value="{{ old('category_metadata.length_cm', $meta['length_cm'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.length_cm') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="category_metadata[framed]" value="0">
                                    <input type="checkbox" name="category_metadata[framed]" value="1"
                                           @checked(old('category_metadata.framed', ($meta['framed'] ?? '0')) == '1')
                                           class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700">Framed</span>
                                </div>
                                @error('category_metadata.framed') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                            </div>
                        </template>

                        {{-- photographic-images --}}
                        <template x-if="slug === 'photographic-images'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image type *</label>
                                    <select name="category_metadata[image_type]" class="mt-1 block w-full rounded-md border-gray-300" required>
                                        <option value="">Select</option>
                                        <option value="Black and White" @selected(old('category_metadata.image_type', $meta['image_type'] ?? '') === 'Black and White')>Black and White</option>
                                        <option value="Colour" @selected(old('category_metadata.image_type', $meta['image_type'] ?? '') === 'Colour')>Colour</option>
                                    </select>
                                    @error('category_metadata.image_type') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Height (cm) *</label>
                                        <input type="number" step="0.01" name="category_metadata[height_cm]"
                                               value="{{ old('category_metadata.height_cm', $meta['height_cm'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.height_cm') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Length (cm) *</label>
                                        <input type="number" step="0.01" name="category_metadata[length_cm]"
                                               value="{{ old('category_metadata.length_cm', $meta['length_cm'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.length_cm') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- sculptures + carvings --}}
                        <template x-if="slug === 'sculptures' || slug === 'carvings'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Material *</label>
                                    <input name="category_metadata[material]"
                                           value="{{ old('category_metadata.material', $meta['material'] ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300" required>
                                    @error('category_metadata.material') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Height (cm) *</label>
                                        <input type="number" step="0.01" name="category_metadata[height_cm]"
                                               value="{{ old('category_metadata.height_cm', $meta['height_cm'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.height_cm') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Length (cm) *</label>
                                        <input type="number" step="0.01" name="category_metadata[length_cm]"
                                               value="{{ old('category_metadata.length_cm', $meta['length_cm'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.length_cm') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Width (cm) *</label>
                                        <input type="number" step="0.01" name="category_metadata[width_cm]"
                                               value="{{ old('category_metadata.width_cm', $meta['width_cm'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.width_cm') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Weight (kg) *</label>
                                        <input type="number" step="0.01" name="category_metadata[weight_kg]"
                                               value="{{ old('category_metadata.weight_kg', $meta['weight_kg'] ?? '') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300" required>
                                        @error('category_metadata.weight_kg') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="slug === null">
                            <div class="text-sm text-gray-600">
                                Select a category to edit required category-specific details.
                            </div>
                        </template>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700">
                            Save Changes
                        </button>

                        <a href="{{ route('admin.lots.show', $lot) }}" class="text-sm underline text-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.lots.destroy', $lot) }}"
                      onsubmit="return confirm('Delete this lot? This cannot be undone.');">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="text-sm text-red-700 underline">
                        Delete lot
                    </button>
                </form>

                <script>
                    function lotEditForm() {
                        return {
                            slug: null,
                            setCategoryFromSelect() {
                                const sel = document.querySelector('select[name="category_id"]');
                                const opt = sel?.selectedOptions?.[0];
                                this.slug = opt?.dataset?.slug ?? null;
                            },
                            init() {
                                this.setCategoryFromSelect();
                            }
                        }
                    }
                </script>

            </div>
        </div>
    </div>
</x-app-layout>
