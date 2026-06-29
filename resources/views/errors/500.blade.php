@extends('layouts.app')

@section('title', 'Erreur serveur')

@section('content')

<div class="max-w-md mx-auto text-center py-16">
    <p class="text-6xl font-bold text-gray-200 mb-2">500</p>
    <h1 class="text-xl font-bold text-gray-900 mb-2">Une erreur est survenue</h1>
    <p class="text-gray-500 text-sm mb-8">
        Quelque chose s'est mal passé de notre côté. Veuillez réessayer dans un instant.
        Si le problème persiste, contactez le support.
    </p>
    <a href="{{ route('home') }}"
        class="bg-primary text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors inline-block">
        Retour à l'accueil
    </a>
</div>

@endsection
