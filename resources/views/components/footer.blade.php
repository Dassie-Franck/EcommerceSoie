<footer style="background: #0a0a0a; color: #e5e5e5; font-family: var(--font-body, 'DM Sans', sans-serif);">

    <style>
        .footer-main {
            max-width: 1280px;
            margin: 0 auto;
            padding: 3.5rem 2.5rem 2rem;
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr 1.5fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .footer-main {
                grid-template-columns: repeat(3, 1fr);
            }
            .footer-col-app   { grid-column: 1 / -1; }
            .footer-col-right { grid-column: 1 / -1; }
        }

        @media (max-width: 640px) {
            .footer-main {
                grid-template-columns: 1fr 1fr;
                padding: 2.5rem 1.25rem 1.5rem;
            }
            .footer-col-app   { grid-column: 1 / -1; }
            .footer-col-right { grid-column: 1 / -1; }
        }

        /* ── Colonne gauche : App ── */
        .footer-app-title {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .footer-app-badges {
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .app-badge {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 6px;
            padding: .5rem .85rem;
            text-decoration: none;
            color: #ffffff;
            width: fit-content;
            transition: border-color .2s, background .2s;
        }

        .app-badge:hover {
            border-color: rgba(255,255,255,.5);
            background: rgba(255,255,255,.05);
        }

        .app-badge svg {
            width: 1.5rem;
            height: 1.5rem;
            flex-shrink: 0;
        }

        .app-badge-text small {
            display: block;
            font-size: .58rem;
            letter-spacing: .05em;
            opacity: .7;
            text-transform: uppercase;
        }

        .app-badge-text strong {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .01em;
        }

        /* ── Colonnes liens ── */
        .footer-col-title {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .footer-col-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: .55rem;
        }

        .footer-col-links a {
            font-size: .8rem;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-col-links a:hover {
            color: #ffffff;
        }

        /* ── Colonne droite : QR code + Newsletter ── */
        .footer-col-right {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Label "Scannez pour visiter" */
        .qr-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
            margin-bottom: .4rem;
        }

        /* QR Code SVG généré via path */
        .qr-block {
            background: #ffffff;
            border-radius: 4px;
            padding: .5rem;
            width: fit-content;
        }

        /* Newsletter */
        .newsletter-block {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .newsletter-label {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
        }

        .newsletter-form {
            display: flex;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 4px;
            overflow: hidden;
        }

        .newsletter-form input {
            flex: 1;
            background: rgba(255,255,255,.06);
            border: none;
            outline: none;
            padding: .6rem .85rem;
            font-size: .78rem;
            color: #ffffff;
            font-family: inherit;
            min-width: 0;
        }

        .newsletter-form input::placeholder {
            color: rgba(255,255,255,.3);
        }

        .newsletter-form button {
            padding: .6rem 1rem;
            background: rgba(255,255,255,.1);
            border: none;
            border-left: 1px solid rgba(255,255,255,.2);
            color: #ffffff;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s;
            white-space: nowrap;
        }

        .newsletter-form button:hover {
            background: rgba(255,255,255,.18);
        }

        /* ── Barre réseaux sociaux ── */
        .footer-social-bar {
            border-top: 1px solid rgba(255,255,255,.08);
            padding: 1.25rem 2.5rem;
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 1.1rem;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            transition: color .2s, transform .2s;
        }

        .social-icon:hover {
            color: #ffffff;
            transform: translateY(-2px);
        }

        .social-icon svg {
            width: 1.15rem;
            height: 1.15rem;
        }

        /* ── Barre copyright ── */
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            padding: 1rem 2.5rem;
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .footer-copy {
            font-size: .72rem;
            color: rgba(255,255,255,.35);
        }

        .footer-legal {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .footer-legal a {
            font-size: .72rem;
            color: rgba(255,255,255,.35);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-legal a:hover {
            color: rgba(255,255,255,.75);
        }
    </style>

    {{-- ── MAIN GRID ── --}}
    <div class="footer-main">

        {{-- COL 1 : App --}}
        <div class="footer-col-app">
            <p class="footer-app-title">Achetez plus vite avec l'app</p>
            <div class="footer-app-badges">

                {{-- App Store --}}
                <a href="#" class="app-badge">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                    <div class="app-badge-text">
                        <small>Download on the</small>
                        <strong>App Store</strong>
                    </div>
                </a>

                {{-- Google Play --}}
                <a href="#" class="app-badge">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3.18 23.76c.3.17.64.22.99.14l12.08-6.98-2.59-2.59-10.48 9.43zm-1.85-20.4A1.99 1.99 0 0 0 1 4.87v14.26c0 .65.32 1.22.82 1.57l.09.06 7.99-7.99v-.19L1.33 3.36zm19.64 8.72l-2.28-1.32-2.88 2.88 2.89 2.89 2.29-1.32a2 2 0 0 0 0-3.13zM4.17.5.09.14C-.11.2-.03.47.1.6l10.51 10.5 2.6-2.6L4.17.5z"/>
                    </svg>
                    <div class="app-badge-text">
                        <small>Get it on</small>
                        <strong>Google Play</strong>
                    </div>
                </a>

            </div>
        </div>

        {{-- COL 2 : Help --}}
        <div>
            <p class="footer-col-title">Aide</p>
            <ul class="footer-col-links">
                <li><a href="#">Centre d'aide</a></li>
                <li><a href="#">Suivre ma commande</a></li>
                <li><a href="#">Informations de livraison</a></li>
                <li><a href="#">Retours & Échanges</a></li>
                <li><a href="#">Contactez-nous</a></li>
            </ul>
        </div>

        {{-- COL 3 : Company --}}
        <div>
            <p class="footer-col-title">AfriSoie</p>
            <ul class="footer-col-links">
                <li><a href="#">Carrières</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Nos artisans</a></li>
                <li><a href="#">Partenariats</a></li>
            </ul>
        </div>

        {{-- COL 4 : Quick Links --}}
        <div>
            <p class="footer-col-title">Liens rapides</p>
            <ul class="footer-col-links">
                <li><a href="#">Guide des tailles</a></li>
                <li><a href="#">Plan du site</a></li>
                <li><a href="#">Cartes cadeaux</a></li>
                <li><a href="#">Vérifier solde carte</a></li>
                <li><a href="#">Blog & Inspirations</a></li>
            </ul>
        </div>

        {{-- COL 5 : QR Code + Newsletter --}}
        <div class="footer-col-right">

          

            {{-- Newsletter --}}
            <div class="newsletter-block">
                <p class="newsletter-label">Newsletter</p>
                <form action="#" method="POST" class="newsletter-form" @submit.prevent>
                    @csrf
                    <input type="email" name="email" placeholder="Votre adresse e-mail">
                    <button type="submit">S'inscrire</button>
                </form>
            </div>

        </div>
    </div>

    {{-- ── RÉSEAUX SOCIAUX ── --}}
    <div class="footer-social-bar">

        {{-- Instagram --}}
        <a href="#" class="social-icon" aria-label="Instagram">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
        </a>

        {{-- TikTok --}}
        <a href="#" class="social-icon" aria-label="TikTok">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/>
            </svg>
        </a>

        {{-- YouTube --}}
        <a href="#" class="social-icon" aria-label="YouTube">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
            </svg>
        </a>

        {{-- Snapchat --}}
        <a href="#" class="social-icon" aria-label="Snapchat">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.166.006C9.845-.022 7.63.92 6.04 2.618 4.672 4.076 3.989 6.012 4.101 7.949l-.003.791c-.009.092-.05.175-.117.235a2.61 2.61 0 0 1-.457.25c-.282.119-.6.225-.962.224a2.33 2.33 0 0 1-.539-.067.8.8 0 0 0-.213-.028c-.206 0-.4.066-.551.195a.737.737 0 0 0-.266.569c0 .439.362.757.714.908.066.028.206.075.373.115a3.74 3.74 0 0 1 .822.278c.108.068.191.174.233.301.078.235-.019.487-.238.756C2.475 13.198 2 14.226 2 15.273c0 1.786 1.449 3.234 3.234 3.234.381 0 .748-.066 1.09-.188.586-.209 1.197-.213 1.652.074.49.308.9.759 1.199 1.26.277.47.606 1.035 1.177 1.461.601.445 1.352.677 2.147.677.794 0 1.545-.232 2.146-.677.572-.426.9-.991 1.178-1.461.299-.501.709-.952 1.199-1.26.455-.287 1.066-.283 1.652-.074.342.122.709.188 1.09.188C21.551 18.507 23 17.059 23 15.273c0-1.047-.475-2.075-1.898-3.795-.219-.269-.316-.521-.238-.756a.656.656 0 0 1 .233-.301 3.74 3.74 0 0 1 .822-.278c.167-.04.307-.087.373-.115.352-.151.714-.469.714-.908a.737.737 0 0 0-.266-.569.852.852 0 0 0-.551-.195.8.8 0 0 0-.213.028 2.33 2.33 0 0 1-.539.067c-.362.001-.68-.105-.962-.224a2.61 2.61 0 0 1-.457-.25.372.372 0 0 1-.117-.235l-.003-.791C20.011 3.985 16.47.05 12.166.006z"/>
            </svg>
        </a>

        {{-- Facebook --}}
        <a href="#" class="social-icon" aria-label="Facebook">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
        </a>

        {{-- Pinterest --}}
        <a href="#" class="social-icon" aria-label="Pinterest">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/>
            </svg>
        </a>

        {{-- WhatsApp --}}
        <a href="#" class="social-icon" aria-label="WhatsApp">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
            </svg>
        </a>

    </div>

    {{-- ── COPYRIGHT ── --}}
    <div class="footer-bottom">
        <span class="footer-copy">© {{ date('Y') }} AfriSoie. Tous droits réservés.</span>
        <div class="footer-legal">
            <a href="#">Promo T&C</a>
            <a href="#">Politique de confidentialité</a>
            <a href="#">Conditions d'utilisation</a>
            <a href="#">Chaînes d'approvisionnement</a>
            <a href="#">Vos choix de confidentialité</a>
        </div>
    </div>

</footer>
