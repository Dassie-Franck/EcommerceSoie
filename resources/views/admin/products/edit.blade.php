@extends('layouts.admin')
@section('title', 'Éditer ' . $product->name)
@section('content')
<div class="max-w-2xl">
    <h1 class="font-heading text-2xl font-semibold mb-6">Éditer : {{ $product->name }}</h1>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div class="form-control">
                    <label class="label"><span class="label-text">Nom</span></label>
                    <input type="text" name="name" class="input input-bordered" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catégorie</span></label>
                    <select name="category_id" class="select select-bordered">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered" rows="5">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Prix (€)</span></label>
                        <input type="number" name="base_price" class="input input-bordered" step="0.01" value="{{ old('base_price', $product->base_price) }}">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tissu</span></label>
                        <input type="text" name="fabric_type" class="input input-bordered" value="{{ old('fabric_type', $product->fabric_type) }}">
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Nouvelles photos</span></label>
                    <input type="file" name="images[]" class="file-input file-input-bordered" multiple accept="image/*">
                </div>
                <div class="flex gap-6">
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" {{ $product->is_active ? 'checked' : '' }}>
                        <span class="label-text">Actif</span>
                    </label>
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_featured" value="1" class="checkbox checkbox-secondary" {{ $product->is_featured ? 'checked' : '' }}>
                        <span class="label-text">Mis en avant</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection