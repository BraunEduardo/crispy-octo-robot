<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'card_numbers' => 'required|integer|digits:16',
            'cvv' => 'required|integer|digits:3',
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
