<!DOCTYPE html>
<html lang="fr" data-theme="afrisoie">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>@yield('title', 'AfriSoie Shop')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-base-100 text-base-content">

    {{-- Barre promotionnelle --}}
    @include('components.topbar')

    {{-- Navigation --}}
    @include('components.navbar')

    {{-- Flash Message --}}
    @include('components.flash-message')

    {{-- Contenu --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    @stack('scripts')
{{-- Bouton WhatsApp Flottant --}}
<style>
    /* Bouton WhatsApp flottant */
    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        z-index: 999;
        transition: all 0.3s ease;
        animation: whatsappPulse 2s infinite;
        overflow: hidden;
    }

    /* Centrage parfait de l'icône - Version corrigée */
    .whatsapp-float i {
        font-size: 34px;
        display: block;
        line-height: 1;
        text-align: center;
        position: relative;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: 0;
        padding: 0;
        transform: translate(0, 0);
    }

    .whatsapp-float:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
        color: white;
    }

    @keyframes whatsappPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.5);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }

    /* Tooltip au survol */
    .whatsapp-float::before {
        content: "Discuter sur WhatsApp";
        position: absolute;
        right: 70px;
        background: #1a1a1a;
        color: white;
        font-size: 12px;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 8px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        font-family: 'Jost', sans-serif;
        letter-spacing: 0.5px;
        pointer-events: none;
        z-index: 1000;
    }

    .whatsapp-float::after {
        content: "";
        position: absolute;
        right: 65px;
        top: 50%;
        transform: translateY(-50%);
        border-width: 6px;
        border-style: solid;
        border-color: transparent transparent transparent #1a1a1a;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .whatsapp-float:hover::before,
    .whatsapp-float:hover::after {
        opacity: 1;
        visibility: visible;
    }

    .whatsapp-float:hover::after {
        right: 64px;
    }

    /* Responsive pour mobile */
    @media (max-width: 768px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
        }

        .whatsapp-float i {
            font-size: 28px;
        }

        .whatsapp-float::before {
            display: none;
        }
    }
</style>

{{-- UN SEUL BOUTON AVEC LE BON NUMÉRO --}}
<a href="https://wa.me/237659210296?text=Bonjour%20je%20viens%20de%20votre%20site%20Eclat%20Soie%20et%20je%20souhaiterais%20avoir%20des%20informations%20sur%20vos%20produits"
   target="_blank"
   rel="noopener noreferrer"
   class="whatsapp-float">
    <i class="fab fa-whatsapp"></i>
</a>

@auth

@endauth

<script>
// Mise à jour du compteur de favoris
function updateWishlistCount(count) {
    const countElement = document.getElementById('wishlist-count');
    if (countElement) {
        countElement.textContent = count;
        if (count === 0) {
            countElement.classList.add('hidden');
        } else {
            countElement.classList.remove('hidden');
        }
    }
}

// Fonction pour ajouter/retirer des favoris depuis n'importe quelle page
function toggleWishlist(variantId, buttonElement = null) {
    fetch('{{ route("shop.wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_variant_id: variantId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour le compteur
            updateWishlistCount(data.count);

            // Mettre à jour l'icône du bouton si fourni
            if (buttonElement) {
                const icon = buttonElement.querySelector('i');
                if (data.added) {
                    icon.className = 'fas fa-heart';
                    buttonElement.classList.add('text-[#9D8E1C]');
                } else {
                    icon.className = 'far fa-heart';
                    buttonElement.classList.remove('text-[#9D8E1C]');
                }
            }

            // Notification optionnelle
            showToast(data.message, data.added ? 'success' : 'info');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Une erreur est survenue', 'error');
    });
}

// Système de notification simple
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} transition-all duration-300 transform translate-x-full`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
</body>
</html>
