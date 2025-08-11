<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'total_amount' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'name is required',
            'name.string' => 'name is not a valid string',
            'name.max' => 'name is too long',
            'total_amount.numeric' => 'total_amount is not a valid number',
            'total_amount.min' => 'total_amount is too short',
        ];
    }
}
