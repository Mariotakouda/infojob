@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
    {{-- Le tableau de bord administrateur complet se trouve sur /admin --}}
    <div class="text-center py-16">
        <p class="text-gray-500 mb-4">Vous êtes redirigé vers le panneau d'administration...</p>
        <a href="{{ route('admin.index') }}" class="group inline-flex items-center gap-1 text-primary font-medium">
            <span class="group-hover:underline">Accéder au panneau d'administration</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
@endsection
