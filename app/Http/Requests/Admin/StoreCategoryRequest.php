<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:2', 'max:100', 'unique:categories,name'],
            'parent_id'   => ['nullable', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'is_active'   => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique'   => 'Cette catégorie existe déjà.',
            'name.min'      => 'Le nom doit contenir au moins 2 caractères.',
            'parent_id.exists' => 'Catégorie parente invalide.',
            'image.image'   => 'Le fichier doit être une image.',
            'image.mimes'   => 'Formats acceptés : JPEG, PNG, WebP.',
            'image.max'     => 'L\'image ne doit pas dépasser 2MB.',
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
