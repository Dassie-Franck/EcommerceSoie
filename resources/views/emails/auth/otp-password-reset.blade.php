@component('mail::message')
# Réinitialisation de mot de passe

Bonjour **{{ $userName }}**,

Voici votre code de vérification :

@component('mail::panel')
# {{ $otp }}
@endcomponent

Ce code est valable **{{ $expiry }}** et ne peut être utilisé qu'**une seule fois**.

> ⚠️ Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.
> Votre mot de passe ne sera pas modifié.

Merci,
{{ config('app.name') }}
@endcomponent
