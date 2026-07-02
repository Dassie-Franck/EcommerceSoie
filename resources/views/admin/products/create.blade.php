@extends('layouts.admin')
@section('title', 'Nouveau produit')
@section('content')
<div class="max-w-2xl">
    <h1 class="font-heading text-2xl font-semibold mb-6">Nouveau produit</h1>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Nom du produit</span></label>
                    <input type="text" name="name" class="input input-bordered" value="{{ old('name') }}" required>
                    @error('name')<p class="text-error text-xs">{{ $message }}</p>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catégorie</span></label>
                    <select name="category_id" class="select select-bordered" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered" rows="5" required>{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Prix de base (€)</span></label>
                        <input type="number" name="base_price" class="input input-bordered" step="0.01" min="0" value="{{ old('base_price') }}" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Type de tissu</span></label>
                        <input type="text" name="fabric_type" class="input input-bordered" value="{{ old('fabric_type') }}" required>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Photos du produit</span></label>
                    <input type="file" name="images[]" class="file-input file-input-bordered" multiple accept="image/*">
                </div>
                <div class="flex gap-6">
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" checked>
                        <span class="label-text">Produit actif</span>
                    </label>
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_featured" value="1" class="checkbox checkbox-secondary">
                        <span class="label-text">Mis en avant</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">Créer le produit</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection