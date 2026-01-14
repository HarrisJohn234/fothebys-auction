<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:users,id'],
            'hammer_price' => ['required', 'numeric', 'min:1'],
        ];
    }
}
