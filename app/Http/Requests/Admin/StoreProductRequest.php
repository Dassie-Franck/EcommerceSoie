<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 🔐 Seul l'admin peut créer un produit
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'min:2', 'max:200'],
            'category_id'       => ['required', 'integer', 'exists:categories,id,is_active,1'],
            'description'       => ['required', 'string', 'min:10', 'max:5000'],
            'base_price'        => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'compare_price'     => ['nullable', 'numeric', 'min:0', 'gt:base_price'],
            'fabric_type'       => ['required', 'string', 'max:100'],
            'origin'            => ['nullable', 'string', 'max:100'],
            'care_instructions' => ['nullable', 'string', 'max:1000'],
            'is_active'         => ['nullable', 'boolean'],
            'is_featured'       => ['nullable', 'boolean'],
            'meta_title'        => ['nullable', 'string', 'max:200'],
            'meta_description'  => ['nullable', 'string', 'max:500'],

            // Images : max 8 images, chaque image max 3MB
            'images'            => ['nullable', 'array', 'max:8'],
            'images.*'          => ['image', 'mimes:jpeg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Le nom du produit est obligatoire.',
            'name.min'               => 'Le nom doit contenir au moins 2 caractères.',
            'name.max'               => 'Le nom ne doit pas dépasser 200 caractères.',
            'category_id.required'   => 'La catégorie est obligatoire.',
            'category_id.exists'     => 'Catégorie invalide ou inactive.',
            'description.required'   => 'La description est obligatoire.',
            'description.min'        => 'La description doit contenir au moins 10 caractères.',
            'base_price.required'    => 'Le prix de base est obligatoire.',
            'base_price.numeric'     => 'Le prix doit être un nombre.',
            'base_price.min'         => 'Le prix ne peut pas être négatif.',
            'compare_price.gt'       => 'Le prix barré doit être supérieur au prix de base.',
            'fabric_type.required'   => 'Le type de tissu est obligatoire.',
            'images.max'             => 'Maximum 8 images autorisées.',
            'images.*.image'         => 'Chaque fichier doit être une image.',
            'images.*.mimes'         => 'Formats acceptés : JPEG, PNG, WebP.',
            'images.*.max'           => 'Chaque image ne doit pas dépasser 3MB.',
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
