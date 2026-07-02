<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // auth géré par middleware
    }

    public function rules(): array
    {
        return [
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'title'   => ['nullable', 'string', 'max:150'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required'  => 'La note est obligatoire.',
            'rating.min'       => 'La note minimale est 1.',
            'rating.max'       => 'La note maximale est 5.',
            'comment.required' => 'Le commentaire est obligatoire.',
            'comment.min'      => 'Le commentaire doit contenir au moins 10 caractères.',
            'comment.max'      => 'Le commentaire ne doit pas dépasser 1000 caractères.',
            'title.max'        => 'Le titre ne doit pas dépasser 150 caractères.',
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
