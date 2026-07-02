<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'              => ['sometimes', 'string', 'min:2', 'max:200'],
            'category_id'       => ['sometimes', 'integer', 'exists:categories,id,is_active,1'],
            'description'       => ['sometimes', 'string', 'min:10', 'max:5000'],
            'base_price'        => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
            'compare_price'     => ['nullable', 'numeric', 'min:0', 'gt:base_price'],
            'fabric_type'       => ['sometimes', 'string', 'max:100'],
            'origin'            => ['nullable', 'string', 'max:100'],
            'care_instructions' => ['nullable', 'string', 'max:1000'],
            'is_active'         => ['nullable', 'boolean'],
            'is_featured'       => ['nullable', 'boolean'],
            'meta_title'        => ['nullable', 'string', 'max:200'],
            'meta_description'  => ['nullable', 'string', 'max:500'],

            // Images supplémentaires
            'images'            => ['nullable', 'array', 'max:8'],
            'images.*'          => ['image', 'mimes:jpeg,png,webp', 'max:3072'],

            // IDs des images à supprimer
            'delete_images'     => ['nullable', 'array'],
            'delete_images.*'   => ['integer', 'exists:product_images,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'compare_price.gt'       => 'Le prix barré doit être supérieur au prix de base.',
            'images.max'             => 'Maximum 8 images autorisées.',
            'images.*.image'         => 'Chaque fichier doit être une image.',
            'images.*.mimes'         => 'Formats acceptés : JPEG, PNG, WebP.',
            'images.*.max'           => 'Chaque image ne doit pas dépasser 3MB.',
            'delete_images.*.exists' => 'Image à supprimer introuvable.',
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
