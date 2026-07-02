<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth déjà géré par le middleware
    }

    public function rules(): array
    {
        return [
            'address_id'       => [
                'required',
                'integer',
                // Vérifie que l'adresse appartient à l'utilisateur connecté
                'exists:addresses,id,user_id,' . $this->user()->id,
            ],
            'shipping_zone_id' => [
                'required',
                'integer',
                'exists:shipping_zones,id,is_active,1', // zone doit être active
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required'       => 'Veuillez sélectionner une adresse de livraison.',
            'address_id.exists'         => 'Adresse invalide ou non autorisée.',
            'shipping_zone_id.required' => 'Veuillez sélectionner un mode de livraison.',
            'shipping_zone_id.exists'   => 'Zone de livraison invalide ou inactive.',
            'notes.max'                 => 'Les notes ne doivent pas dépasser 500 caractères.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
