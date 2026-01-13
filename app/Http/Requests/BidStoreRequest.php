<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BidStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Any authenticated user can place a bid in Sprint 1.
        // If you want stricter rules later, gate by role here.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'max_bid_amount' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'max_bid_amount.required' => 'Please enter a maximum bid amount.',
            'max_bid_amount.numeric'  => 'Maximum bid amount must be a number.',
            'max_bid_amount.min'      => 'Maximum bid amount must be at least 1.',
        ];
    }
}
