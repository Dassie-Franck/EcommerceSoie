<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email'    => 'Format d\'email invalide.',
            // Note : on ne révèle PAS si l'email existe ou non en prod
            // Le message 'exists' est intentionnellement générique
            'email.exists'   => 'Aucun compte associé à cet email.',
        ];
    }
}
