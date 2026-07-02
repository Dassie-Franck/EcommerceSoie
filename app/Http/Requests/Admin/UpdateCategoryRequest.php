<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        // Récupère l'ID de la catégorie en cours de modification
        $categoryId = $this->route('category')?->id;

        return [
            'name'        => [
                'sometimes', 'string', 'min:2', 'max:100',
                // Unique sauf pour elle-même
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'parent_id'   => [
                'nullable', 'integer',
                'exists:categories,id',
                // Empêcher une catégorie d'être son propre parent
                Rule::notIn([$categoryId]),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'is_active'   => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique'      => 'Cette catégorie existe déjà.',
            'parent_id.not_in' => 'Une catégorie ne peut pas être son propre parent.',
            'parent_id.exists' => 'Catégorie parente invalide.',
            'image.mimes'      => 'Formats acceptés : JPEG, PNG, WebP.',
            'image.max'        => 'L\'image ne doit pas dépasser 2MB.',
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
