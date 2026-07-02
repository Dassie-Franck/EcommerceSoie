@extends('layouts.admin')
@section('title', 'Produits')

@push('head')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
<div style="background:#FAFAF7;min-height:100vh;padding:2rem;">

    @if(session('success'))
    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;border-radius:0.75rem;font-size:0.875rem;font-weight:500;margin-bottom:1.5rem;background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:1.875rem;font-weight:600;color:#1C1C1A;">Produits</h1>
            <p style="font-size:0.875rem;margin-top:0.125rem;color:#7A7870;">{{ $products->total() }} références</p>
        </div>
        <button onclick="ouvrirModal('modal-produit-creation')" style="background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1.25rem;border-radius:0.75rem;font-size:0.875rem;font-weight:500;text-transform:uppercase;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouveau produit
        </button>
    </div>

    <div style="background:#fff;border:1px solid #E5E1D8;border-radius:16px;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead style="background:#FAFAF7;border-bottom:1px solid #E5E1D8;">
                    <tr>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Produit</th>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Catégorie</th>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Prix</th>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Variantes</th>
                        <th style="text-align:left;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Statut</th>
                        <th style="text-align:right;padding:0.75rem 1.25rem;font-size:0.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#7A7870;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    @php $img = $product->images->first(); @endphp
                    <tr style="border-bottom:1px solid #E5E1D8;">
                        <td style="padding:0.875rem 1.25rem;font-size:0.875rem;color:#3A3A38;">
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                @if($img)
                                <img src="{{ Storage::url($img->path) }}" alt="{{ $product->name }}" onclick="ouvrirModalImage('{{ Storage::url($img->path) }}', '{{ addslashes($product->name) }}')" style="width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid #E5E1D8;cursor:pointer;">
                                @else
                                <div style="width:44px;height:44px;border-radius:8px;background:#F5F2E8;display:flex;align-items:center;justify-content:center;border:1px solid #C8B84A55;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9D8E1C" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18"/><circle cx="8.5" cy="8.5" r="2.5"/><path d="M21.5 15.5L15.5 9.5L8.5 16.5L4.5 12.5"/></svg>
                                </div>
                                @endif
                                <div>
                                    <p style="font-weight:500;font-size:0.875rem;color:#1C1C1A;">{{ $product->name }}</p>
                                    <p style="font-size:0.75rem;color:#7A7870;">{{ $product->fabric_type }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding:0.875rem 1.25rem;font-size:0.875rem;color:#7A7870;">{{ $product->category->name ?? '—' }}</td>
                        <td style="padding:0.875rem 1.25rem;font-size:0.875rem;font-weight:600;color:#9D8E1C;">{{ number_format($product->base_price, 0, ',', ' ') }} €</td>
                        <td style="padding:0.875rem 1.25rem;font-size:0.75rem;color:#7A7870;">
                            @if($product->variants->count() > 0)
                                <span style="background:#F5F2E8;padding:2px 8px;border-radius:12px;">{{ $product->variants->count() }} variante(s)</span>
                            @else
                                <span style="color:#9F1239;">—</span>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1.25rem;">
                            <div style="display:flex;flex-wrap:wrap;gap:0.25rem;">
                                <span style="background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:99px;">{{ $product->is_active ? 'Actif' : 'Inactif' }}</span>
                                @if($product->is_featured)
                                <span style="background:#F5F2E8;color:#9D8E1C;border:1px solid #C8B84A55;font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:99px;">Vedette</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:0.875rem 1.25rem;text-align:right;">
                            <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.5rem;">
                                <button onclick="ouvrirModalEdition({{ $product->id }})" style="background:transparent;border:1.5px solid #9D8E1C;color:#9D8E1C;cursor:pointer;display:inline-flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:0.5rem;font-size:0.75rem;font-weight:500;text-transform:uppercase;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3l4 4-7 7H10v-4l7-7z"/><path d="M5 19h14"/></svg>
                                    Éditer
                                </button>
                                <button onclick="ouvrirModalSuppression({{ $product->id }}, '{{ addslashes($product->name) }}')" style="background:transparent;border:1.5px solid #FECDD3;color:#9F1239;cursor:pointer;display:inline-flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:0.5rem;font-size:0.75rem;font-weight:500;text-transform:uppercase;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    Suppr.
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid #E5E1D8;">
            {{ $products->links() }}
        </div>
    </div>
</div>

{{-- MODAL CREATION --}}
<div id="modal-produit-creation" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;align-items:center;justify-content:center;overflow:auto;">
    <div style="background:white;border-radius:20px;width:95%;max-width:1000px;max-height:95vh;overflow:auto;margin:2rem auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:1.5rem;border-bottom:1px solid #E5E1D8;">
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:#1C1C1A;">Nouveau produit</h2>
            <button onclick="fermerModal('modal-produit-creation')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
        </div>

        <form id="createProductForm" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" style="padding:1.5rem;">
            @csrf

            <div style="margin-bottom:2rem;">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;color:#9D8E1C;">Informations générales</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Nom *</label><input type="text" name="name" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Catégorie *</label><select name="category_id" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"><option value="">Sélectionner</option>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Type de tissu *</label><input type="text" name="fabric_type" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Origine</label><input type="text" name="origin" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Prix (€)</label><input type="number" name="base_price" step="1" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Prix barré</label><input type="number" name="compare_price" step="1" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                </div>
                <div style="margin-top:1rem;"><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Instructions d'entretien</label><input type="text" name="care_instructions" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                <div style="margin-top:1rem;"><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Description *</label><textarea name="description" rows="4" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></textarea></div>
            </div>

            <div style="margin-bottom:2rem;">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;color:#9D8E1C;">Couleurs et Variantes</h3>

                <div style="margin-bottom:1rem;">
                    <button type="button" onclick="ajouterCouleur()" style="background:#9D8E1C;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;margin-right:0.5rem;">+ Ajouter une couleur</button>
                    <button type="button" onclick="ajouterTaille()" style="background:#9D8E1C;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;">+ Ajouter une taille</button>
                </div>

                <div id="couleurs-container" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;"></div>
                <div id="tailles-container" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;"></div>

                <div id="variantes-container" style="margin-top:1rem;"></div>
            </div>

            <div style="margin-bottom:2rem;">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;color:#9D8E1C;">Images</h3>
                <button type="button" onclick="ajouterImage()" style="background:#9D8E1C;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;">+ Ajouter une image</button>
                <div id="images-list" style="display:flex;flex-wrap:wrap;gap:1rem;margin-top:1rem;"></div>
            </div>

            <div style="display:flex;gap:1.5rem;margin-bottom:1.5rem;">
                <label><input type="checkbox" name="is_active" value="1" checked> Actif</label>
                <label><input type="checkbox" name="is_featured" value="1"> Mis en avant</label>
            </div>

            <div style="display:flex;gap:1rem;padding-top:1rem;border-top:1px solid #ddd;">
                <button type="submit" style="background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:white;padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Créer</button>
                <button type="button" onclick="fermerModal('modal-produit-creation')" style="background:transparent;border:1.5px solid #9D8E1C;color:#9D8E1C;padding:0.75rem 1.5rem;border-radius:8px;cursor:pointer;">Annuler</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITION --}}
<div id="modal-produit-edition" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;align-items:center;justify-content:center;overflow:auto;">
    <div style="background:white;border-radius:20px;width:95%;max-width:1000px;max-height:95vh;overflow:auto;margin:2rem auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:1.5rem;border-bottom:1px solid #E5E1D8;">
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:#1C1C1A;">Éditer le produit</h2>
            <button onclick="fermerModal('modal-produit-edition')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
        </div>

        <form id="editProductForm" method="POST" enctype="multipart/form-data" style="padding:1.5rem;">
            @csrf
            @method('PATCH')
            <input type="hidden" id="edit_product_id" name="product_id">

            <div style="margin-bottom:2rem;">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;color:#9D8E1C;">Informations générales</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Nom *</label><input type="text" id="edit_name" name="name" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Catégorie *</label><select id="edit_category_id" name="category_id" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;">@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Type de tissu *</label><input type="text" id="edit_fabric_type" name="fabric_type" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Origine</label><input type="text" id="edit_origin" name="origin" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Prix (€)</label><input type="number" id="edit_base_price" name="base_price" step="1" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                    <div><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Prix barré</label><input type="number" id="edit_compare_price" name="compare_price" step="1" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                </div>
                <div style="margin-top:1rem;"><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Instructions d'entretien</label><input type="text" id="edit_care_instructions" name="care_instructions" style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></div>
                <div style="margin-top:1rem;"><label style="display:block;margin-bottom:0.5rem;font-weight:600;">Description *</label><textarea id="edit_description" name="description" rows="4" required style="width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;"></textarea></div>
            </div>

            <div style="margin-bottom:2rem;">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;color:#9D8E1C;">Couleurs et Variantes</h3>

                <div style="margin-bottom:1rem;">
                    <button type="button" onclick="ajouterCouleurEdition()" style="background:#9D8E1C;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;margin-right:0.5rem;">+ Ajouter une couleur</button>
                    <button type="button" onclick="ajouterTailleEdition()" style="background:#9D8E1C;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;">+ Ajouter une taille</button>
                </div>

                <div id="edit_couleurs-container" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;"></div>
                <div id="edit_tailles-container" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;"></div>

                <div id="edit_variantes-container" style="margin-top:1rem;"></div>
            </div>

            <div style="margin-bottom:2rem;">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;color:#9D8E1C;">Images</h3>
                <button type="button" onclick="ajouterImageEdition()" style="background:#9D8E1C;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;">+ Ajouter une image</button>
                <div id="edit_images-list" style="display:flex;flex-wrap:wrap;gap:1rem;margin-top:1rem;"></div>
                <input type="hidden" name="delete_images_ids" id="delete_images_ids" value="">
            </div>

            <div style="display:flex;gap:1.5rem;margin-bottom:1.5rem;">
                <label><input type="checkbox" id="edit_is_active" name="is_active" value="1"> Actif</label>
                <label><input type="checkbox" id="edit_is_featured" name="is_featured" value="1"> Mis en avant</label>
            </div>

            <div style="display:flex;gap:1rem;padding-top:1rem;border-top:1px solid #ddd;">
                <button type="submit" style="background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:white;padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Enregistrer</button>
                <button type="button" onclick="fermerModal('modal-produit-edition')" style="background:transparent;border:1.5px solid #9D8E1C;color:#9D8E1C;padding:0.75rem 1.5rem;border-radius:8px;cursor:pointer;">Annuler</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL SUPPRESSION --}}
<div id="modal-produit-suppression" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:20px;width:90%;max-width:400px;text-align:center;padding:2rem;">
        <div style="width:60px;height:60px;background:#FEE2E2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
        </div>
        <h3 style="font-size:1.25rem;font-weight:600;margin-bottom:0.5rem;">Confirmer la suppression</h3>
        <p style="color:#666;margin-bottom:1.5rem;">Supprimer "<span id="suppression_nom"></span>" ?</p>
        <form id="formSuppression" method="POST" style="display:flex;gap:1rem;justify-content:center;">
            @csrf @method('DELETE')
            <button type="submit" style="background:#DC2626;color:white;padding:0.5rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Supprimer</button>
            <button type="button" onclick="fermerModal('modal-produit-suppression')" style="background:#eee;padding:0.5rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Annuler</button>
        </form>
    </div>
</div>

{{-- MODAL IMAGE --}}
<div id="modal-image" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:999999;align-items:center;justify-content:center;">
    <div style="position:relative;max-width:90%;max-height:90%;">
        <img id="modal_image_src" src="" style="max-width:100%;max-height:90vh;border-radius:8px;">
        <p id="modal_image_nom" style="color:white;text-align:center;margin-top:1rem;"></p>
        <button onclick="fermerModal('modal-image')" style="position:absolute;top:-40px;right:0;background:none;border:none;color:white;font-size:2rem;cursor:pointer;">&times;</button>
    </div>
</div>
<script>
// ============================================
// VARIABLES
// ============================================
let couleurs = [];
let tailles = [];
let images = [];

let editCouleurs = [];
let editTailles = [];
let editImages = [];
let editVariantsExistants = [];

// ============================================
// FONCTIONS MODALES
// ============================================
function ouvrirModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
}
function fermerModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.style.display = 'none'; document.body.style.overflow = ''; }
}

// ============================================
// CRÉATION - COULEURS
// ============================================
function ajouterCouleur() {
    const nom = prompt("Nom de la couleur :");
    const hex = prompt("Code hexadécimal (ex: #DC2626) :") || "#9D8E1C";
    if (nom && nom.trim()) {
        couleurs.push({ name: nom.trim(), hex: hex });
        afficherCouleurs();
        genererTableauVariantes();
    }
}
function supprimerCouleur(index) {
    couleurs.splice(index, 1);
    afficherCouleurs();
    genererTableauVariantes();
}
function afficherCouleurs() {
    const container = document.getElementById('couleurs-container');
    if (!container) return;
    container.innerHTML = '';
    couleurs.forEach((c, i) => {
        const div = document.createElement('div');
        div.style.cssText = 'display:inline-flex;align-items:center;gap:0.5rem;background:#F5F2E8;border-radius:20px;padding:0.4rem 0.8rem;';
        div.innerHTML = `<span style="width:12px;height:12px;border-radius:50%;background:${c.hex};border:1px solid #ddd;"></span><span>${c.name}</span><button type="button" onclick="supprimerCouleur(${i})" style="background:none;border:none;cursor:pointer;color:#9F1239;">&times;</button>`;
        container.appendChild(div);
    });
}

// ============================================
// CRÉATION - TAILLES
// ============================================
function ajouterTaille() {
    const taille = prompt("Taille (S, M, L, XL) :");
    if (taille && taille.trim()) {
        tailles.push(taille.trim().toUpperCase());
        afficherTailles();
        genererTableauVariantes();
    }
}
function supprimerTaille(index) {
    tailles.splice(index, 1);
    afficherTailles();
    genererTableauVariantes();
}
function afficherTailles() {
    const container = document.getElementById('tailles-container');
    if (!container) return;
    container.innerHTML = '';
    tailles.forEach((t, i) => {
        const div = document.createElement('div');
        div.style.cssText = 'display:inline-flex;align-items:center;gap:0.5rem;background:#F5F2E8;border-radius:20px;padding:0.4rem 0.8rem;';
        div.innerHTML = `<span>${t}</span><button type="button" onclick="supprimerTaille(${i})" style="background:none;border:none;cursor:pointer;color:#9F1239;">&times;</button>`;
        container.appendChild(div);
    });
}

// ============================================
// CRÉATION - TABLEAU VARIANTES
// ============================================
function genererTableauVariantes() {
    const container = document.getElementById('variantes-container');
    if (!container) return;

    if (couleurs.length === 0 || tailles.length === 0) {
        container.innerHTML = '<div style="padding:2rem;text-align:center;background:#FAFAF7;border-radius:8px;color:#7A7870;">Ajoutez des couleurs et des tailles pour créer les variantes</div>';
        return;
    }

    let html = '<table style="width:100%;border-collapse:collapse;background:#FAFAF7;border-radius:8px;"><thead><tr style="background:#E5E1D8;"><th style="padding:0.75rem;">Taille</th>';
    couleurs.forEach(c => { html += `<th style="padding:0.75rem;">${c.name}</th>`; });
    html += '<th style="padding:0.75rem;">Action</th></tr></thead><tbody>';

    tailles.forEach((taille, ti) => {
        html += `<tr><td style="padding:0.75rem;font-weight:600;">${taille}</td>`;
        couleurs.forEach((couleur, ci) => {
            html += `<td style="padding:0.75rem;">
                        <input type="number" name="variants[${ti}][${ci}][stock]" placeholder="Stock" value="0" style="width:80px;padding:0.5rem;border:1px solid #ddd;border-radius:6px;">
                        <input type="hidden" name="variants[${ti}][${ci}][size]" value="${taille}">
                        <input type="hidden" name="variants[${ti}][${ci}][color]" value="${couleur.name}">
                        <input type="hidden" name="variants[${ti}][${ci}][color_hex]" value="${couleur.hex}">
                     </td>`;
        });
        html += `<td style="padding:0.75rem;"><button type="button" onclick="supprimerTaille(${ti})" style="background:#FEE2E2;border:none;padding:0.5rem 1rem;border-radius:6px;cursor:pointer;">Supprimer</button></td></tr>`;
    });
    html += '</tbody></table>';
    container.innerHTML = html;
}

// ============================================
// CRÉATION - IMAGES (CORRIGÉ)
// ============================================
function ajouterImage() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            images.push({ file: file, name: file.name, primary: images.length === 0, preview: URL.createObjectURL(file) });
            afficherImages();
        }
    };
    input.click();
}

function supprimerImage(index) {
    if (images[index].preview) URL.revokeObjectURL(images[index].preview);
    images.splice(index, 1);
    if (images.length > 0 && !images.some(i => i.primary)) images[0].primary = true;
    afficherImages();
}

function definirImagePrincipale(index) {
    images.forEach((img, i) => img.primary = (i === index));
    afficherImages();
}

function afficherImages() {
    const container = document.getElementById('images-list');
    if (!container) return;
    container.innerHTML = '';

    // Créer ou récupérer le champ caché pour l'index de l'image principale
    let primaryIndexInput = document.getElementById('primary_image_index');
    if (!primaryIndexInput) {
        primaryIndexInput = document.createElement('input');
        primaryIndexInput.type = 'hidden';
        primaryIndexInput.name = 'primary_image_index';
        primaryIndexInput.id = 'primary_image_index';
        container.parentNode.appendChild(primaryIndexInput);
    }

    images.forEach((img, i) => {
        const div = document.createElement('div');
        div.style.cssText = `position:relative;width:100px;border:2px solid ${img.primary ? '#9D8E1C' : '#E5E1D8'};border-radius:8px;overflow:hidden;margin:0.5rem;display:inline-block;vertical-align:top;`;
        div.innerHTML = `<img src="${img.preview}" style="width:100%;height:100px;object-fit:cover;">
                        <div style="position:absolute;top:0;right:0;background:rgba(0,0,0,0.7);padding:2px 5px;font-size:10px;">
                            ${img.primary ? '<span style="color:#9D8E1C;">⭐ Principale</span>' : '<button type="button" onclick="definirImagePrincipale('+i+')" style="background:none;border:none;color:white;cursor:pointer;">Définir</button>'}
                        </div>
                        <button type="button" onclick="supprimerImage(${i})" style="position:absolute;bottom:0;right:0;background:#DC2626;color:white;border:none;padding:2px 8px;cursor:pointer;">✕</button>
                        </div>`;
        container.appendChild(div);
    });

    // Mettre à jour le champ caché avec l'index de l'image principale
    const primaryIndex = images.findIndex(img => img.primary === true);
    primaryIndexInput.value = primaryIndex >= 0 ? primaryIndex : 0;
}

// ============================================
// CRÉATION - SOUMISSION
// ============================================
document.getElementById('createProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // Ajouter les variantes
    let variantIndex = 0;
    tailles.forEach(taille => {
        couleurs.forEach(couleur => {
            const ti = tailles.indexOf(taille);
            const ci = couleurs.indexOf(couleur);
            const stockInput = document.querySelector(`input[name="variants[${ti}][${ci}][stock]"]`);
            if (stockInput && parseInt(stockInput.value) > 0) {
                formData.append(`variants[${variantIndex}][size]`, taille);
                formData.append(`variants[${variantIndex}][color]`, couleur.name);
                formData.append(`variants[${variantIndex}][color_hex]`, couleur.hex);
                formData.append(`variants[${variantIndex}][stock_quantity]`, stockInput.value);
                variantIndex++;
            }
        });
    });

    // Ajouter les images
    images.forEach((img, i) => {
        if (img.file) {
            formData.append(`images[]`, img.file);
        }
    });

    // Ajouter les couleurs
    formData.append('colors_json', JSON.stringify(couleurs));

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) window.location.reload();
        else alert('Erreur: ' + (data.message || 'Erreur'));
    })
    .catch(() => this.submit());
});

// ============================================
// ÉDITION - CHARGEMENT
// ============================================
async function ouvrirModalEdition(productId) {
    try {
        const response = await fetch(`/admin/products/${productId}/edit-data`);
        const data = await response.json();

        document.getElementById('edit_product_id').value = data.product.id;
        document.getElementById('edit_name').value = data.product.name;
        document.getElementById('edit_category_id').value = data.product.category_id;
        document.getElementById('edit_description').value = data.product.description;
        document.getElementById('edit_base_price').value = data.product.base_price;
        document.getElementById('edit_compare_price').value = data.product.compare_price || '';
        document.getElementById('edit_fabric_type').value = data.product.fabric_type;
        document.getElementById('edit_origin').value = data.product.origin || '';
        document.getElementById('edit_care_instructions').value = data.product.care_instructions || '';
        document.getElementById('edit_is_active').checked = data.product.is_active;
        document.getElementById('edit_is_featured').checked = data.product.is_featured;

        editCouleurs = data.colors || [];
        editTailles = data.sizes || [];
        editVariantsExistants = data.variants || [];

        afficherCouleursEdition();
        afficherTaillesEdition();
        genererTableauVariantesEdition();

        // ✅ CORRECTION : Nettoyer les chemins des images
        editImages = data.images.map(img => ({
            id: img.id,
            path: img.path,  // Garder le chemin original
            primary: img.is_primary === 1,
            is_new: false,
            file: null,
            preview: null
        }));

        // ✅ Vérifier le chemin dans la console
        console.log('Images chargées:', editImages);

        afficherImagesEdition();

        document.getElementById('editProductForm').action = `/admin/products/${productId}`;
        ouvrirModal('modal-produit-edition');
    } catch (error) {
        console.error(error);
        alert('Erreur chargement du produit');
    }
}

// ============================================
// ÉDITION - COULEURS
// ============================================
function ajouterCouleurEdition() {
    const nom = prompt("Nom de la couleur :");
    const hex = prompt("Code hexadécimal :") || "#9D8E1C";
    if (nom && nom.trim()) {
        editCouleurs.push({ name: nom.trim(), hex: hex });
        afficherCouleursEdition();
        genererTableauVariantesEdition();
    }
}
function supprimerCouleurEdition(index) {
    editCouleurs.splice(index, 1);
    afficherCouleursEdition();
    genererTableauVariantesEdition();
}
function afficherCouleursEdition() {
    const container = document.getElementById('edit_couleurs-container');
    if (!container) return;
    container.innerHTML = '';
    editCouleurs.forEach((c, i) => {
        const div = document.createElement('div');
        div.style.cssText = 'display:inline-flex;align-items:center;gap:0.5rem;background:#F5F2E8;border-radius:20px;padding:0.4rem 0.8rem;';
        div.innerHTML = `<span style="width:12px;height:12px;border-radius:50%;background:${c.hex};"></span><span>${c.name}</span><button type="button" onclick="supprimerCouleurEdition(${i})" style="background:none;border:none;cursor:pointer;">&times;</button>`;
        container.appendChild(div);
    });
}

// ============================================
// ÉDITION - TAILLES
// ============================================
function ajouterTailleEdition() {
    const taille = prompt("Taille :");
    if (taille && taille.trim()) {
        editTailles.push(taille.trim().toUpperCase());
        afficherTaillesEdition();
        genererTableauVariantesEdition();
    }
}
function supprimerTailleEdition(index) {
    editTailles.splice(index, 1);
    afficherTaillesEdition();
    genererTableauVariantesEdition();
}
function afficherTaillesEdition() {
    const container = document.getElementById('edit_tailles-container');
    if (!container) return;
    container.innerHTML = '';
    editTailles.forEach((t, i) => {
        const div = document.createElement('div');
        div.style.cssText = 'display:inline-flex;align-items:center;gap:0.5rem;background:#F5F2E8;border-radius:20px;padding:0.4rem 0.8rem;';
        div.innerHTML = `<span>${t}</span><button type="button" onclick="supprimerTailleEdition(${i})" style="background:none;border:none;cursor:pointer;">&times;</button>`;
        container.appendChild(div);
    });
}

// ============================================
// ÉDITION - TABLEAU VARIANTES
// ============================================
function genererTableauVariantesEdition() {
    const container = document.getElementById('edit_variantes-container');
    if (!container) return;

    if (editCouleurs.length === 0 || editTailles.length === 0) {
        container.innerHTML = '<div style="padding:2rem;text-align:center;background:#FAFAF7;border-radius:8px;color:#7A7870;">Ajoutez des couleurs et des tailles</div>';
        return;
    }

    let html = '<table style="width:100%;border-collapse:collapse;background:#FAFAF7;border-radius:8px;"><thead><tr style="background:#E5E1D8;"><th style="padding:0.75rem;">Taille</th>';
    editCouleurs.forEach(c => { html += `<th style="padding:0.75rem;">${c.name}</th>`; });
    html += '<th style="padding:0.75rem;">Action</th></tr></thead><tbody>';

    editTailles.forEach((taille, ti) => {
        html += `<tr><td style="padding:0.75rem;font-weight:600;">${taille}</td>`;
        editCouleurs.forEach((couleur, ci) => {
            const existing = editVariantsExistants.find(v => v.size === taille && v.color === couleur.name);
            html += `<td style="padding:0.75rem;">
                        <input type="number" name="edit_variants[${ti}][${ci}][stock]" value="${existing ? existing.stock_quantity : 0}" style="width:80px;padding:0.5rem;border:1px solid #ddd;border-radius:6px;">
                        <input type="hidden" name="edit_variants[${ti}][${ci}][size]" value="${taille}">
                        <input type="hidden" name="edit_variants[${ti}][${ci}][color]" value="${couleur.name}">
                        <input type="hidden" name="edit_variants[${ti}][${ci}][color_hex]" value="${couleur.hex}">
                        <input type="hidden" name="edit_variants[${ti}][${ci}][id]" value="${existing ? existing.id : ''}">
                     </td>`;
        });
        html += `<td style="padding:0.75rem;"><button type="button" onclick="supprimerTailleEdition(${ti})" style="background:#FEE2E2;border:none;padding:0.5rem 1rem;border-radius:6px;cursor:pointer;">Supprimer</button></td></tr>`;
    });
    html += '</tbody></table>';
    container.innerHTML = html;
}

// ============================================
// ÉDITION - IMAGES (CORRIGÉ)
// ============================================
function ajouterImageEdition() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            editImages.push({
                file: file,
                name: file.name,
                primary: editImages.length === 0,
                preview: URL.createObjectURL(file),
                is_new: true,
                id: null,
                path: null
            });
            afficherImagesEdition();
        }
    };
    input.click();
}

function supprimerImageEdition(index) {
    const img = editImages[index];
    if (img.preview) URL.revokeObjectURL(img.preview);
    if (img.id && !img.is_new) {
        const deleteIds = document.getElementById('delete_images_ids');
        const ids = deleteIds.value ? deleteIds.value.split(',') : [];
        ids.push(img.id);
        deleteIds.value = ids.join(',');
    }
    editImages.splice(index, 1);
    if (editImages.length > 0 && !editImages.some(i => i.primary)) editImages[0].primary = true;
    afficherImagesEdition();
}

function definirImagePrincipaleEdition(index) {
    editImages.forEach((img, i) => img.primary = (i === index));
    afficherImagesEdition();
}

function afficherImagesEdition() {
    const container = document.getElementById('edit_images-list');
    if (!container) return;
    container.innerHTML = '';

    // Créer ou récupérer le champ caché pour l'ID de l'image principale
    let primaryImageIdInput = document.getElementById('primary_image_id');
    if (!primaryImageIdInput) {
        primaryImageIdInput = document.createElement('input');
        primaryImageIdInput.type = 'hidden';
        primaryImageIdInput.name = 'primary_image_id';
        primaryImageIdInput.id = 'primary_image_id';
        container.parentNode.appendChild(primaryImageIdInput);
    }

    editImages.forEach((img, i) => {
        let src = '';
        if (img.preview) {
            src = img.preview;
        } else if (img.path) {
            // ✅ CORRECTION : Enlever le double storage/
            // Si img.path contient déjà le chemin complet
            let cleanPath = img.path;
            if (cleanPath.startsWith('/storage/')) {
                cleanPath = cleanPath.substring(9); // Enlève '/storage/'
            }
            if (cleanPath.startsWith('storage/')) {
                cleanPath = cleanPath.substring(8); // Enlève 'storage/'
            }
            src = '/storage/' + cleanPath;
        }

        const div = document.createElement('div');
        div.setAttribute('data-image-id', img.id || 'new_' + i);
        div.style.cssText = `position:relative;width:100px;border:2px solid ${img.primary ? '#9D8E1C' : '#E5E1D8'};border-radius:8px;overflow:hidden;margin:0.5rem;display:inline-block;vertical-align:top;`;
        div.innerHTML = `<img src="${src}" style="width:100%;height:100px;object-fit:cover;" onerror="this.src='https://placehold.co/400x400?text=Erreur'">
                        <div style="position:absolute;top:0;right:0;background:rgba(0,0,0,0.7);padding:2px 5px;font-size:10px;">
                            ${img.primary ? '<span style="color:#9D8E1C;">⭐ Principale</span>' : '<button type="button" onclick="definirImagePrincipaleEdition('+i+')" style="background:none;border:none;color:white;cursor:pointer;">Définir</button>'}
                        </div>
                        <button type="button" onclick="supprimerImageEdition(${i})" style="position:absolute;bottom:0;right:0;background:#DC2626;color:white;border:none;padding:2px 8px;cursor:pointer;">✕</button>
                        ${!img.is_new ? '<span style="position:absolute;bottom:0;left:0;background:#9D8E1C;color:white;padding:2px 5px;font-size:8px;">Existante</span>' : ''}
                        </div>`;
        container.appendChild(div);
    });

    // Mettre à jour le champ caché avec l'ID de l'image principale
    const primaryImage = editImages.find(img => img.primary === true && !img.is_new && img.id);
    if (primaryImageIdInput && primaryImage && primaryImage.id) {
        primaryImageIdInput.value = primaryImage.id;
    } else if (primaryImageIdInput) {
        primaryImageIdInput.value = '';
    }
}

// ============================================
// ÉDITION - SOUMISSION
// ============================================
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('_method', 'PATCH');

    // Récupérer les variantes
    let variantIndex = 0;
    editTailles.forEach(taille => {
        editCouleurs.forEach(couleur => {
            const ti = editTailles.indexOf(taille);
            const ci = editCouleurs.indexOf(couleur);
            const stockInput = document.querySelector(`input[name="edit_variants[${ti}][${ci}][stock]"]`);
            const idInput = document.querySelector(`input[name="edit_variants[${ti}][${ci}][id]"]`);
            if (stockInput && parseInt(stockInput.value) > 0) {
                formData.append(`variants[${variantIndex}][size]`, taille);
                formData.append(`variants[${variantIndex}][color]`, couleur.name);
                formData.append(`variants[${variantIndex}][color_hex]`, couleur.hex);
                formData.append(`variants[${variantIndex}][stock_quantity]`, stockInput.value);
                if (idInput && idInput.value) formData.append(`variants[${variantIndex}][id]`, idInput.value);
                variantIndex++;
            }
        });
    });

    // Ajouter les nouvelles images
    editImages.forEach((img, i) => {
        if (img.is_new && img.file) {
            formData.append(`new_images[]`, img.file);
            formData.append(`new_images_primary[${i}]`, img.primary ? '1' : '0');
        }
    });

    const keepIds = editImages.filter(img => !img.is_new && img.id).map(img => img.id);
    formData.append('keep_images_ids', JSON.stringify(keepIds));
    formData.append('colors_json', JSON.stringify(editCouleurs));

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) window.location.reload();
        else alert('Erreur: ' + (data.message || 'Erreur'));
    })
    .catch(() => this.submit());
});

// ============================================
// AUTRES FONCTIONS
// ============================================
function ouvrirModalSuppression(id, name) {
    document.getElementById('formSuppression').action = `/admin/products/${id}`;
    document.getElementById('suppression_nom').textContent = name;
    ouvrirModal('modal-produit-suppression');
}

function ouvrirModalImage(src, name) {
    document.getElementById('modal_image_src').src = src;
    document.getElementById('modal_image_nom').textContent = name;
    ouvrirModal('modal-image');
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});

@if($errors->any()) ouvrirModal('modal-produit-creation'); @endif
</script>
@endsection
