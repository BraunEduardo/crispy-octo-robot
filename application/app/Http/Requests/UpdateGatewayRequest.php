<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGatewayRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'priority' => [
                'sometimes',
                'integer',
                Rule::unique('gateways')->ignore($this->route('id')),
            ],
            'url' => 'sometimes|url',
            'data' => 'sometimes|array',
        ];
    }
}
