@extends('layouts.admin')
@section('title', 'Nouvelle catégorie')
@section('content')
<div class="max-w-md">
    <h1 class="font-heading text-2xl font-semibold mb-6">Nouvelle catégorie</h1>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Nom</span></label>
                    <input type="text" name="name" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catégorie parente (optionnel)</span></label>
                    <select name="parent_id" class="select select-bordered">
                        <option value="">Aucune (catégorie principale)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="cursor-pointer label gap-2">
                    <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" checked>
                    <span class="label-text">Catégorie active</span>
                </label>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Créer</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection