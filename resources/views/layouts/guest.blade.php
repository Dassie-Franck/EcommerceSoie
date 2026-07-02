<!DOCTYPE html>
<html lang="fr" data-theme="afrisoie">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — AfriSoie Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-base-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-4">
        <div class="text-center mb-8">
            <a href="{{ route('shop.home') }}" class="font-heading text-3xl font-semibold text-primary">AfriSoie</a>
        </div>
        @include('components.flash-message')
        @yield('content')
    </div>
</body>
</html>