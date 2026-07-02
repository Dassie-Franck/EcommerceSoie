<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\s\-]+$/u'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()       // au moins une lettre
                    ->mixedCase()     // majuscule + minuscule
                    ->numbers()       // au moins un chiffre
                    ->uncompromised() // vérifie si le mot de passe a fuité (haveibeenpwned)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Le nom est obligatoire.',
            'name.min'          => 'Le nom doit contenir au moins 2 caractères.',
            'name.max'          => 'Le nom ne doit pas dépasser 100 caractères.',
            'name.regex'        => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
            'email.required'    => 'L\'adresse email est obligatoire.',
            'email.email'       => 'Format d\'email invalide.',
            'email.unique'      => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed'=> 'Les mots de passe ne correspondent pas.',
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
