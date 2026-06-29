@extends('layouts.app')

@section('title', 'Accès refusé')

@section('content')

<div class="max-w-md mx-auto text-center py-16">
    <p class="text-6xl font-bold text-gray-200 mb-2">403</p>
    <h1 class="text-xl font-bold text-gray-900 mb-2">Accès refusé</h1>
    <p class="text-gray-500 text-sm mb-8">
        Vous n'avez pas l'autorisation d'accéder à cette page ou d'effectuer cette action.
    </p>
    <a href="{{ route('home') }}"
        class="bg-primary text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors inline-block">
        Retour à l'accueil
    </a>
</div>

@endsection
