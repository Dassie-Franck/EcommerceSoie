<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.min'      => 'La quantité minimale est 1.',
            'quantity.max'      => 'La quantité maximale est 10.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json(['success' => false, 'errors' => $validator->errors()], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
