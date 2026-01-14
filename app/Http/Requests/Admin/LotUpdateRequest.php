<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LotStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Convert the category_metadata textarea (JSON string) into an array before validation.
     */
    protected function prepareForValidation(): void
    {
        $raw = $this->input('category_metadata');

        if ($raw === null || trim((string) $raw) === '') {
            $this->merge(['category_metadata' => []]);
            return;
        }

        if (is_array($raw)) {
            return;
        }

        $decoded = json_decode((string) $raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            $this->merge(['category_metadata' => null]);
            return;
        }

        $this->merge(['category_metadata' => $decoded]);
    }

    public function rules(): array
    {
        return [
            'artist_name' => ['required', 'string', 'max:255'],
            'year_produced' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'subject_classification' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'estimate_low' => ['required', 'numeric', 'min:0'],
            'estimate_high' => ['required', 'numeric', 'gte:estimate_low'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'string', 'max:50'],
            'category_metadata' => ['required', 'array'],

            // NEW: optional lot image
            'image' => ['nullable', 'image', 'max:5120'], // up to 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'category_metadata.array' => 'Category metadata must be valid JSON (e.g. {"medium":"oil"}).',
            'category_metadata.required' => 'Category metadata is required (use {} if none).',
        ];
    }
}
