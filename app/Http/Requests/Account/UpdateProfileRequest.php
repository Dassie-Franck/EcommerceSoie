<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\s\-]+$/u'],
            'phone'  => ['nullable', 'string', 'max:30', 'regex:/^[+\d\s\-()]{7,20}$/'],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,webp',
                'max:2048', // 2MB max
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.min'      => 'Le nom doit contenir au moins 2 caractères.',
            'name.max'      => 'Le nom ne doit pas dépasser 100 caractères.',
            'name.regex'    => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
            'phone.regex'   => 'Numéro de téléphone invalide.',
            'phone.max'     => 'Le numéro ne doit pas dépasser 30 caractères.',
            'avatar.image'  => 'Le fichier doit être une image.',
            'avatar.mimes'  => 'Formats acceptés : JPEG, PNG, WebP.',
            'avatar.max'    => 'L\'image ne doit pas dépasser 2MB.',
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
