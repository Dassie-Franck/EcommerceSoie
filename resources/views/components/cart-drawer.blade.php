{{-- Cart Drawer — intégrable dans le layout via @include --}}
<div x-data="{ open: false }">
    <button @click="open = true" class="btn btn-ghost">Panier</button>
    <div class="fixed inset-0 z-50" x-show="open" x-cloak>
        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
        <div class="absolute right-0 top-0 h-full w-80 bg-base-100 shadow-xl p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-heading text-xl">Mon panier</h2>
                <button @click="open = false" class="btn btn-ghost btn-sm">✕</button>
            </div>
            <p class="text-base-content/60 text-sm">Votre panier est vide.</p>
        </div>
    </div>
</div>