@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
    {{-- Le tableau de bord administrateur complet se trouve sur /admin --}}
    <div class="text-center py-16">
        <p class="text-gray-500 mb-4">Vous êtes redirigé vers le panneau d'administration...</p>
        <a href="{{ route('admin.index') }}" class="text-primary font-medium hover:underline">
            Accéder au panneau d'administration →
        </a>
    </div>
@endsection
