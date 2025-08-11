<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCreateRequest extends FormRequest
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
            'total_amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'name is required',
            'name.string' => 'name is not a valid string',
            'name.max' => 'name is too long',
            'total_amount.required' => 'total_amount is required',
            'total_amount.numeric' => 'total_amount is not a valid number',
            'total_amount.min' => 'total_amount is too short',
            'created_by.required' => 'created_by is required',
            'created_by.string' => 'created_by is not a valid string',
            'created_by.max' => 'created_by is too long',
        ];
    }
}
