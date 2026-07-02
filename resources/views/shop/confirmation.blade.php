@extends('layouts.app')
@section('title', 'Commande confirmée')
@section('content')
<div class="container mx-auto px-4 py-20 text-center max-w-lg">
    <div class="text-6xl mb-6">✓</div>
    <h1 class="font-heading text-3xl font-semibold text-success mb-4">Commande confirmée !</h1>
    <p class="text-base-content/70 mb-8">Merci pour votre commande. Vous allez recevoir un email de confirmation.</p>
    <div class="flex gap-4 justify-center">
        <a href="{{ route('account.orders') }}" class="btn btn-primary">Mes commandes</a>
        <a href="{{ route('shop.home') }}" class="btn btn-ghost">Continuer mes achats</a>
    </div>
</div>
@endsection
<section class="container mx-auto px-4 py-16">

    <h2 class="text-3xl md:text-5xl font-bold uppercase mb-8">
        The Trend Report
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <a href="#" class="group">
            <div class="overflow-hidden">
                <img src="TON_IMAGE_1"
                     alt=""
                     class="w-full h-[500px] md:h-[600px] object-cover transition duration-500 group-hover:scale-105">
            </div>
            <p class="text-center font-medium mt-3 text-lg">
                Robes Élégantes →
            </p>
        </a>

        <a href="#" class="group">
            <div class="overflow-hidden">
                <img src="TON_IMAGE_2"
                     alt=""
                     class="w-full h-[500px] md:h-[600px] object-cover transition duration-500 group-hover:scale-105">
            </div>
            <p class="text-center font-medium mt-3 text-lg">
                Collection Soie →
            </p>
        </a>

        <a href="#" class="group">
            <div class="overflow-hidden">
                <img src="TON_IMAGE_3"
                     alt=""
                     class="w-full h-[500px] md:h-[600px] object-cover transition duration-500 group-hover:scale-105">
            </div>
            <p class="text-center font-medium mt-3 text-lg">
                Nouveautés →
            </p>
        </a>

        <a href="#" class="group">
            <div class="overflow-hidden">
                <img src="TON_IMAGE_4"
                     alt=""
                     class="w-full h-[500px] md:h-[600px] object-cover transition duration-500 group-hover:scale-105">
            </div>
            <p class="text-center font-medium mt-3 text-lg">
                Accessoires →
            </p>
        </a>

    </div>

</section>
