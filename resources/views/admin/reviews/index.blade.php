@extends('layouts.admin')
@section('title', 'Avis clients')

@push('head')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#9D8E1C;--gold-dark:#584F05;--gold-bg:#F5F2E8;--white:#fff;--cream:#FAFAF7;--ink:#1C1C1A;--ink-soft:#3A3A38;--muted:#7A7870;--border:#E5E1D8;--border-gold:#C8B84A55;}
body{font-family:'Jost',sans-serif;}
.font-display{font-family:'Cormorant Garamond',serif;}
.review-card{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:1.25rem;transition:border-color .2s,box-shadow .2s;}
.review-card:hover{border-color:var(--border-gold);box-shadow:0 4px 20px #9D8E1C0D;}
.star-on{color:var(--gold);}
.star-off{color:var(--border);}
.badge-approved{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:99px;text-transform:uppercase;letter-spacing:.05em;}
.badge-pending{background:#FFF8E6;color:#92400E;border:1px solid #FDE68A;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:99px;text-transform:uppercase;letter-spacing:.05em;}
.btn-approve{background:transparent;border:1.5px solid #BBF7D0;color:#166534;border-radius:8px;padding:5px 12px;font-size:.75rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:background .2s,color .2s;display:flex;align-items:center;gap:4px;}
.btn-approve:hover{background:#166534;color:#fff;}
.btn-delete{background:transparent;border:1.5px solid #FECDD3;color:#9F1239;border-radius:8px;padding:5px 12px;font-size:.75rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:background .2s,color .2s;display:flex;align-items:center;gap:4px;}
.btn-delete:hover{background:#9F1239;color:#fff;}
.modal-overlay{position:fixed;inset:0;background:#00000055;backdrop-filter:blur(4px);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;opacity:0;pointer-events:none;transition:opacity .25s;}
.modal-overlay.open{opacity:1;pointer-events:all;}
.modal-box{background:var(--white);border-radius:20px;border:1px solid var(--border);width:100%;max-width:400px;transform:translateY(20px) scale(.97);transition:transform .25s;box-shadow:0 24px 64px #00000022;}
.modal-overlay.open .modal-box{transform:translateY(0) scale(1);}
/* Filtre tabs */
.filter-tab{padding:.4rem 1rem;border-radius:99px;font-size:.75rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;border:1px solid var(--border);background:var(--white);color:var(--muted);transition:background .2s,color .2s,border-color .2s;}
.filter-tab.active,.filter-tab:hover{background:var(--ink);color:#fff;border-color:var(--ink);}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.anim{animation:fadeUp .4s ease both;}
</style>
@endpush

@section('content')
<div style="background:var(--cream);min-height:100vh;padding:2rem;">

    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium mb-6 anim"
         style="background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;">
        <i data-lucide="check-circle-2" class="w-4 h-4"></i>{{ session('success') }}
    </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6 anim">
        <div>
            <h1 class="font-display text-3xl font-semibold" style="color:var(--ink);">Avis clients</h1>
            <p class="text-sm mt-0.5" style="color:var(--muted);">
                {{ $reviews->total() }} avis ·
                <span style="color:#92400E;">
                    {{ $reviews->getCollection()->where('is_approved', false)->count() }} en attente
                </span>
            </p>
        </div>

        {{-- Filtres rapides --}}
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.reviews.index') }}"
               class="filter-tab {{ !request('filter') ? 'active' : '' }}">
                Tous
            </a>
            <a href="{{ route('admin.reviews.index', ['filter'=>'pending']) }}"
               class="filter-tab {{ request('filter')==='pending' ? 'active' : '' }}">
                En attente
            </a>
            <a href="{{ route('admin.reviews.index', ['filter'=>'approved']) }}"
               class="filter-tab {{ request('filter')==='approved' ? 'active' : '' }}">
                Approuvés
            </a>
        </div>
    </div>

    {{-- Grille d'avis --}}
    @if($reviews->isEmpty())
        <div class="text-center py-20 anim" style="color:var(--muted);">
            <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
            <p>Aucun avis pour le moment</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($reviews as $review)
            <div class="review-card anim flex flex-col justify-between gap-4">

                {{-- En-tête avis --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                             style="background:linear-gradient(135deg,var(--gold),var(--gold-dark));">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color:var(--ink);">
                                {{ $review->user->name }}
                            </p>
                            <p class="text-xs" style="color:var(--muted);">
                                {{ $review->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <span class="{{ $review->is_approved ? 'badge-approved' : 'badge-pending' }}">
                        {{ $review->is_approved ? 'Approuvé' : 'En attente' }}
                    </span>
                </div>

                {{-- Étoiles + produit --}}
                <div>
                    <div class="flex gap-0.5 mb-1">
                        @for($s = 1; $s <= 5; $s++)
                            <i data-lucide="star"
                               class="w-4 h-4 {{ $s <= $review->rating ? 'star-on fill-current' : 'star-off' }}">
                            </i>
                        @endfor
                        <span class="text-xs ml-1 font-medium" style="color:var(--muted);">{{ $review->rating }}/5</span>
                    </div>

                    <p class="text-xs font-medium uppercase tracking-wide mb-1" style="color:var(--gold);">
                        {{ $review->product->name }}
                    </p>

                    @if($review->title)
                        <p class="text-sm font-semibold mb-1" style="color:var(--ink);">{{ $review->title }}</p>
                    @endif

                    <p class="text-sm leading-relaxed line-clamp-3" style="color:var(--ink-soft);">
                        {{ $review->comment }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 pt-3" style="border-top:1px solid var(--border);">
                    @if(!$review->is_approved)
                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="flex-1">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-approve w-full justify-center">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Approuver
                            </button>
                        </form>
                    @endif
                    <button onclick="openDeleteModal({{ $review->id }})"
                            class="btn-delete {{ $review->is_approved ? 'flex-1 justify-center' : '' }}">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        @if($review->is_approved) Supprimer @endif
                    </button>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">{{ $reviews->links() }}</div>
    @endif

</div>

{{-- ══ MODAL : CONFIRMER SUPPRESSION AVIS ══ --}}
<div id="modal-delete" class="modal-overlay" onclick="if(event.target===this){closeModal()}">
    <div class="modal-box">
        <div class="px-6 py-8 text-center">
            <div style="width:56px;height:56px;border-radius:16px;background:#FFF1F2;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i data-lucide="trash-2" class="w-6 h-6" style="color:#9F1239;"></i>
            </div>
            <h2 class="font-display text-2xl font-semibold mb-2" style="color:var(--ink);">
                Supprimer cet avis ?
            </h2>
            <p class="text-sm mb-6" style="color:var(--muted);">
                Cette action est irréversible.
            </p>
            <form id="delete-form" method="POST" action="">
                @csrf @method('DELETE')
                <div class="flex gap-3">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold uppercase tracking-widest"
                            style="background:#9F1239;color:#fff;border:none;cursor:pointer;">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="flex-1 py-3 rounded-xl text-sm font-medium uppercase tracking-widest"
                            style="background:transparent;border:1.5px solid var(--gold);color:var(--gold);cursor:pointer;">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

function openDeleteModal(id) {
    document.getElementById('delete-form').action = `/admin/reviews/${id}`;
    document.getElementById('modal-delete').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('modal-delete').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });
</script>
@endsection
