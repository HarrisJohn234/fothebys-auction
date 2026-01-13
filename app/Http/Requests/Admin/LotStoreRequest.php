<?php

namespace App\Http\Requests\Admin;

use App\Domain\Categories\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LotStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $base = [
            'artist_name' => ['required','string','max:255'],
            'year_produced' => ['required','integer','min:1000','max:' . (int) now()->format('Y')],
            'subject_classification' => ['required','string','max:50'],
            'description' => ['required','string'],
            'auction_date' => ['nullable','date'],

            'estimate_low' => ['required','integer','min:0'],
            'estimate_high' => ['nullable','integer','gte:estimate_low'],

            'category_id' => ['required','exists:categories,id'],
            'auction_id' => ['nullable','exists:auctions,id'],

            'status' => ['required', Rule::in(['PENDING','IN_AUCTION','SOLD','WITHDRAWN','ARCHIVED'])],

            'category_metadata' => ['required','array'],
        ];

        return array_merge($base, $this->categorySpecificRules());
    }

    private function categorySpecificRules(): array
    {
        $category = Category::find($this->input('category_id'));
        $slug = $category?->slug;

        return match ($slug) {
            'drawings' => [
                'category_metadata.medium' => ['required','string','max:50'], // pencil/ink/charcoal/other
                'category_metadata.framed' => ['required','boolean'],
                'category_metadata.height_cm' => ['required','numeric','min:0'],
                'category_metadata.length_cm' => ['required','numeric','min:0'],
            ],
            'paintings' => [
                'category_metadata.medium' => ['required','string','max:50'], // oil/acrylic/watercolour/other
                'category_metadata.framed' => ['required','boolean'],
                'category_metadata.height_cm' => ['required','numeric','min:0'],
                'category_metadata.length_cm' => ['required','numeric','min:0'],
            ],
            'photographic-images' => [
                'category_metadata.image_type' => ['required', Rule::in(['Black and White','Colour'])],
                'category_metadata.height_cm' => ['required','numeric','min:0'],
                'category_metadata.length_cm' => ['required','numeric','min:0'],
            ],
            'sculptures' => [
                'category_metadata.material' => ['required','string','max:50'], // bronze/marble/pewter/other
                'category_metadata.height_cm' => ['required','numeric','min:0'],
                'category_metadata.length_cm' => ['required','numeric','min:0'],
                'category_metadata.width_cm' => ['required','numeric','min:0'],
                'category_metadata.weight_kg' => ['required','numeric','min:0'],
            ],
            'carvings' => [
                'category_metadata.material' => ['required','string','max:50'], // oak/beech/pine/willow/other
                'category_metadata.height_cm' => ['required','numeric','min:0'],
                'category_metadata.length_cm' => ['required','numeric','min:0'],
                'category_metadata.width_cm' => ['required','numeric','min:0'],
                'category_metadata.weight_kg' => ['required','numeric','min:0'],
            ],
            default => [
                'category_metadata._' => ['prohibited'],
            ],
        };
    }
}
