@extends('layouts.admin')
@section('title', 'Catégories')

@push('head')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
<div style="background:#FAFAF7;min-height:100vh;padding:2rem;">

    {{-- Flash --}}
    @if(session('success'))
    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;border-radius:0.75rem;font-size:0.875rem;font-weight:500;margin-bottom:1.5rem;background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- En-tête --}}
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:1.875rem;font-weight:600;color:#1C1C1A;">Catégories</h1>
            <p style="font-size:0.875rem;margin-top:0.125rem;color:#7A7870;">{{ $categories->count() }} catégories</p>
        </div>
        <button onclick="ouvrirModal('modal-categorie-creation')" style="background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:#fff;border:none;letter-spacing:.06em;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1.25rem;border-radius:0.75rem;font-size:0.875rem;font-weight:500;text-transform:uppercase;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle catégorie
        </button>
    </div>

    {{-- Tableau --}}
    <div style="background:#fff;border:1px solid #E5E1D8;border-radius:16px;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead style="background:#FAFAF7;border-bottom:1px solid #E5E1D8;">
                    <tr>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Nom</th>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Catégorie parente</th>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Produits</th>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Statut</th>
                        <th style="text-align:right;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr style="border-bottom:1px solid #E5E1D8;">
                        <td style="padding:0.875rem 1.25rem;font-size:0.875rem;color:#3A3A38;">
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <div style="width:32px;height:32px;border-radius:8px;background:#F5F2E8;display:flex;align-items:center;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9D8E1C" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                </div>
                                <span style="font-weight:500;color:#1C1C1A;">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td style="padding:0.875rem 1.25rem;font-size:0.875rem;color:#7A7870;">{{ $cat->parent->name ?? '—' }}</td>
                        <td style="padding:0.875rem 1.25rem;">
                            <span style="background:#F5F2E8;color:#9D8E1C;border:1px solid #C8B84A55;font-size:0.7rem;font-weight:700;padding:2px 8px;border-radius:99px;">{{ $cat->products_count }}</span>
                        </td>
                        <td style="padding:0.875rem 1.25rem;">
                            <span style="background:{{ $cat->is_active ? '#F0FDF4' : '#FFF1F2' }};color:{{ $cat->is_active ? '#166534' : '#9F1239' }};border:1px solid {{ $cat->is_active ? '#BBF7D0' : '#FECDD3' }};font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:99px;">
                                {{ $cat->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td style="padding:0.875rem 1.25rem;text-align:right;">
                            <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.5rem;">
                                <button onclick="ouvrirModalEdition({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->parent_id ?? 'null' }}, {{ $cat->is_active ? 'true' : 'false' }})"
                                        style="background:transparent;border:1.5px solid #9D8E1C;color:#9D8E1C;cursor:pointer;display:inline-flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:0.5rem;font-size:0.75rem;font-weight:500;text-transform:uppercase;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3l4 4-7 7H10v-4l7-7z"/><path d="M5 19h14"/></svg>
                                    Éditer
                                </button>
                                <button onclick="ouvrirModalSuppression({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->products_count }})"
                                        style="background:transparent;border:1.5px solid #FECDD3;color:#9F1239;cursor:pointer;display:inline-flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:0.5rem;font-size:0.75rem;font-weight:500;text-transform:uppercase;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    Suppr.
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:3rem;color:#7A7870;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin:0 auto 1rem;"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            <p>Aucune catégorie pour le moment</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- MODAL CREATION CATEGORIE --}}
{{-- ============================================= --}}
<div id="modal-categorie-creation" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;width:90%;max-width:500px;max-height:90vh;overflow:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:1.5rem;border-bottom:1px solid #E5E1D8;">
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:#1C1C1A;">Nouvelle catégorie</h2>
            <button onclick="fermerModal('modal-categorie-creation')" style="background:none;border:none;font-size:1.8rem;cursor:pointer;color:#7A7870;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" style="padding:1.5rem;">
            @csrf

            <div style="margin-bottom:1rem;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:600;font-size:0.8rem;color:#7A7870;">NOM DE LA CATÉGORIE *</label>
                <input type="text" name="name" required style="width:100%;padding:0.75rem;border:1px solid #E5E1D8;background:#FAFAF7;border-radius:10px;">
                @error('name')<small style="color:#9F1239;">{{ $message }}</small>@enderror
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:600;font-size:0.8rem;color:#7A7870;">CATÉGORIE PARENTE (OPTIONNEL)</label>
                <select name="parent_id" style="width:100%;padding:0.75rem;border:1px solid #E5E1D8;background:#FAFAF7;border-radius:10px;">
                    <option value="">Aucune — catégorie principale</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width:18px;height:18px;">
                    <span style="font-weight:500;">Catégorie active</span>
                </label>
            </div>

            <div style="display:flex;gap:1rem;padding-top:1rem;border-top:1px solid #E5E1D8;">
                <button type="submit" style="background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:white;padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;flex:1;">Créer</button>
                <button type="button" onclick="fermerModal('modal-categorie-creation')" style="background:transparent;border:1.5px solid #9D8E1C;color:#9D8E1C;padding:0.75rem 1.5rem;border-radius:8px;cursor:pointer;">Annuler</button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================= --}}
{{-- MODAL EDITION CATEGORIE --}}
{{-- ============================================= --}}
<div id="modal-categorie-edition" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;width:90%;max-width:500px;max-height:90vh;overflow:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:1.5rem;border-bottom:1px solid #E5E1D8;">
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:#1C1C1A;">Éditer la catégorie</h2>
            <button onclick="fermerModal('modal-categorie-edition')" style="background:none;border:none;font-size:1.8rem;cursor:pointer;color:#7A7870;">&times;</button>
        </div>
        <form id="formEditionCategorie" method="POST" style="padding:1.5rem;">
            @csrf
            @method('PUT')

            <div style="margin-bottom:1rem;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:600;font-size:0.8rem;color:#7A7870;">NOM DE LA CATÉGORIE *</label>
                <input type="text" id="edit_categorie_nom" name="name" required style="width:100%;padding:0.75rem;border:1px solid #E5E1D8;background:#FAFAF7;border-radius:10px;">
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:600;font-size:0.8rem;color:#7A7870;">CATÉGORIE PARENTE (OPTIONNEL)</label>
                <select id="edit_categorie_parent" name="parent_id" style="width:100%;padding:0.75rem;border:1px solid #E5E1D8;background:#FAFAF7;border-radius:10px;">
                    <option value="">Aucune — catégorie principale</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                    <input type="checkbox" id="edit_categorie_actif" name="is_active" value="1" style="width:18px;height:18px;">
                    <span style="font-weight:500;">Catégorie active</span>
                </label>
            </div>

            <div style="display:flex;gap:1rem;padding-top:1rem;border-top:1px solid #E5E1D8;">
                <button type="submit" style="background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:white;padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;flex:1;">Enregistrer</button>
                <button type="button" onclick="fermerModal('modal-categorie-edition')" style="background:transparent;border:1.5px solid #9D8E1C;color:#9D8E1C;padding:0.75rem 1.5rem;border-radius:8px;cursor:pointer;">Annuler</button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================= --}}
{{-- MODAL SUPPRESSION CATEGORIE --}}
{{-- ============================================= --}}
<div id="modal-categorie-suppression" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;width:90%;max-width:400px;text-align:center;">
        <div style="padding:2rem;">
            <div style="width:60px;height:60px;background:#FEE2E2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
            </div>
            <h3 style="font-size:1.25rem;font-weight:600;margin-bottom:0.5rem;">Confirmer la suppression</h3>
            <p style="color:#666;margin-bottom:1rem;">Voulez-vous vraiment supprimer la catégorie <strong id="suppression_categorie_nom"></strong> ?</p>
            <div id="suppression_categorie_warning" style="display:none;background:#FEF3C7;color:#92400E;padding:0.75rem;border-radius:8px;margin-bottom:1rem;font-size:0.8rem;">
                ⚠️ Cette catégorie contient des produits. La suppression sera bloquée.
            </div>
            <form id="formSuppressionCategorie" method="POST" style="display:flex;gap:1rem;justify-content:center;">
                @csrf
                @method('DELETE')
                <button type="submit" id="suppression_categorie_btn" style="background:#DC2626;color:white;padding:0.5rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Supprimer</button>
                <button type="button" onclick="fermerModal('modal-categorie-suppression')" style="background:#eee;padding:0.5rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Annuler</button>
            </form>
        </div>
    </div>
</div>

<script>
// Fonctions pour les modals
function ouvrirModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function fermerModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Fermer en cliquant sur l'overlay
document.addEventListener('click', function(e) {
    if (e.target && e.target.style && e.target.style.display === 'flex' &&
        (e.target.id === 'modal-categorie-creation' ||
         e.target.id === 'modal-categorie-edition' ||
         e.target.id === 'modal-categorie-suppression')) {
        fermerModal(e.target.id);
    }
});

// Fermer avec Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        fermerModal('modal-categorie-creation');
        fermerModal('modal-categorie-edition');
        fermerModal('modal-categorie-suppression');
    }
});

// Ouvrir modal édition
function ouvrirModalEdition(id, name, parentId, isActive) {
    const form = document.getElementById('formEditionCategorie');
    form.action = '/admin/categories/' + id;

    document.getElementById('edit_categorie_nom').value = name;
    document.getElementById('edit_categorie_actif').checked = isActive;

    const selectParent = document.getElementById('edit_categorie_parent');
    for (let i = 0; i < selectParent.options.length; i++) {
        if (parseInt(selectParent.options[i].value) === parentId) {
            selectParent.selectedIndex = i;
            break;
        }
    }

    ouvrirModal('modal-categorie-edition');
}

// Ouvrir modal suppression
function ouvrirModalSuppression(id, name, produitsCount) {
    const form = document.getElementById('formSuppressionCategorie');
    form.action = '/admin/categories/' + id;

    document.getElementById('suppression_categorie_nom').textContent = name;

    const warning = document.getElementById('suppression_categorie_warning');
    const btn = document.getElementById('suppression_categorie_btn');

    if (produitsCount > 0) {
        warning.style.display = 'block';
        btn.disabled = true;
        btn.style.opacity = '0.5';
        btn.style.cursor = 'not-allowed';
    } else {
        warning.style.display = 'none';
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }

    ouvrirModal('modal-categorie-suppression');
}

// Initialiser Lucide
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
});

// Ouvrir modal création si erreurs
@if($errors->any())
    ouvrirModal('modal-categorie-creation');
@endif
</script>
@endsection
