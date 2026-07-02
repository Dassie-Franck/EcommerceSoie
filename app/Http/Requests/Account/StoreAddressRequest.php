<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name'   => ['required', 'string', 'max:150'],
            'phone'       => ['required', 'string', 'max:30', 'regex:/^[+\d\s\-()]{7,20}$/'],
            'street'      => ['required', 'string', 'max:255'],
            'city'        => ['required', 'string', 'max:100'],
            'state'       => ['nullable', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\s\-]{2,10}$/i'],
            'country'     => ['required', 'string', 'max:100'],
            'is_default'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required'   => 'Le nom complet est obligatoire.',
            'phone.required'       => 'Le numéro de téléphone est obligatoire.',
            'phone.regex'          => 'Numéro de téléphone invalide.',
            'street.required'      => 'L\'adresse est obligatoire.',
            'city.required'        => 'La ville est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'postal_code.regex'    => 'Code postal invalide.',
            'country.required'     => 'Le pays est obligatoire.',
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
