@csrf

@if ($errors->any())
    <div class="mb-4 text-sm text-red-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm mb-1">Artist name</label>
        <input name="artist_name" value="{{ old('artist_name', $lot->artist_name ?? '') }}" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Year produced</label>
        <input name="year_produced" type="number" value="{{ old('year_produced', $lot->year_produced ?? '') }}" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Subject classification</label>
        <input name="subject_classification" value="{{ old('subject_classification', $lot->subject_classification ?? '') }}" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Category</label>
        <select name="category_id" class="w-full border rounded p-2" required>
            <option value="">Select…</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected((string)old('category_id', $lot->category_id ?? '') === (string)$category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm mb-1">Estimate low</label>
        <input name="estimate_low" type="number" step="0.01" min="0"
               value="{{ old('estimate_low', $lot->estimate_low ?? '') }}"
               class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Estimate high</label>
        <input name="estimate_high" type="number" step="0.01" min="0"
               value="{{ old('estimate_high', $lot->estimate_high ?? '') }}"
               class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block text-sm mb-1">Status</label>
        <input name="status" value="{{ old('status', $lot->status ?? 'PENDING') }}" class="w-full border rounded p-2" required>
        <p class="text-xs text-gray-500 mt-1">Example: PENDING, IN_AUCTION, SOLD, WITHDRAWN, ARCHIVED</p>
    </div>
</div>

<div class="mt-4">
    <label class="block text-sm mb-1">Description</label>
    <textarea name="description" rows="5" class="w-full border rounded p-2" required>{{ old('description', $lot->description ?? '') }}</textarea>
</div>

{{-- Category metadata placeholder (Sprint 1: free-form JSON via textarea) --}}
<div class="mt-4">
    <label class="block text-sm mb-1">Category metadata (JSON)</label>
    <textarea name="category_metadata" rows="5" class="w-full border rounded p-2"
              placeholder='{"medium":"oil","framed":true,"height_cm":50,"length_cm":70}'>{{ old('category_metadata', isset($lot) ? json_encode($lot->category_metadata, JSON_PRETTY_PRINT) : '') }}</textarea>
    <p class="text-xs text-gray-500 mt-1">Validation per category can be added later.</p>
</div>

<div class="mt-6 flex gap-2">
    <button class="border rounded px-4 py-2">Save</button>
    <a href="{{ route('admin.lots.index') }}" class="border rounded px-4 py-2">Cancel</a>
</div>
