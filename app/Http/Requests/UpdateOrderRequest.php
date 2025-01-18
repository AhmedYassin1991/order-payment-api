<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOrderRequest extends FormRequest
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
            'items' => 'sometimes|array',
            'items.*.product_name' => 'required_with:items|string',
            'items.*.quantity' => 'required_with:items|numeric|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
            'status' => 'sometimes|in:pending,confirmed,cancelled',
        ];
    }

    /**
     * Customize the error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'items.array' => 'The items must be an array.',
            'items.*.product_name.required_with' => 'Each item must have a product name.',
            'items.*.product_name.string' => 'The product name must be a string.',
            'items.*.quantity.required_with' => 'Each item must have a quantity.',
            'items.*.quantity.numeric' => 'The quantity must be a number.',
            'items.*.quantity.min' => 'The quantity must be at least 1.',
            'items.*.price.required_with' => 'Each item must have a price.',
            'items.*.price.numeric' => 'The price must be a number.',
            'items.*.price.min' => 'The price must be at least 0.',
            'status.in' => 'The status must be one of: pending, confirmed, cancelled.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
