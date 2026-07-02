<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // anonyme ou connecté
    }

    public function rules(): array
    {
        return [
            'variant_id' => ['required', 'integer', 'exists:product_variants,id,is_active,1'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'variant_id.required' => 'Veuillez sélectionner une variante.',
            'variant_id.exists'   => 'Ce produit n\'est plus disponible.',
            'quantity.required'   => 'La quantité est obligatoire.',
            'quantity.min'        => 'La quantité minimale est 1.',
            'quantity.max'        => 'La quantité maximale est 10.',
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
