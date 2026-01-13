<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AuctionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        // Trim status/title just to prevent whitespace bugs
        if ($this->has('title')) {
            $this->merge(['title' => trim((string) $this->input('title'))]);
        }
        if ($this->has('status')) {
            $this->merge(['status' => trim((string) $this->input('status'))]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'status' => ['required', 'string', 'max:50'],

            // lots[] are optional; used for assignment
            'lots' => ['nullable', 'array'],
            'lots.*' => ['integer', 'exists:lots,id'],
        ];
    }
}
