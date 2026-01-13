<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BidStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'max_bid_amount' => ['required', 'integer', 'min:1'],
        ];
    }
}
