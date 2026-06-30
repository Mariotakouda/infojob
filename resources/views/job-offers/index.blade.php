@extends('layouts.app')

@section('title', 'Offres d\'emploi')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Offres d'emploi & chantiers</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $offres->total() }} offre(s) disponible(s)</p>
    </div>
</div>

{{-- Filtres --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">Tous les types</option>
        <option value="CDI" {{ request('type') === 'CDI' ? 'selected' : '' }}>CDI</option>
        <option value="CDD" {{ request('type') === 'CDD' ? 'selected' : '' }}>CDD</option>
        <option value="Stage" {{ request('type') === 'Stage' ? 'selected' : '' }}>Stage</option>
        <option value="Prestation_Artisanale" {{ request('type') === 'Prestation_Artisanale' ? 'selected' : '' }}>Prestation artisanale</option>
    </select>
    <input
        type="text"
        name="lieu"
        value="{{ request('lieu') }}"
        placeholder="Filtrer par ville..."
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
    >
    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">Filtrer</button>
    @if(request()->hasAny(['type', 'lieu']))
        <a href="{{ route('job-offers.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">Réinitialiser</a>
    @endif
</form>

{{-- Grille d'offres --}}
@forelse($offres as $offre)
    <div class="card-lift bg-white border border-gray-200 rounded-xl p-5 mb-4 hover:border-primary transition-colors animate-fade-up">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $offre->type_contrat === 'Prestation_Artisanale' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $offre->typeContratLabel() }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $offre->institution->ville }}</span>
                </div>
                <h2 class="font-semibold text-gray-900 text-lg">
                    <a href="{{ route('job-offers.show', $offre) }}" class="hover:text-primary transition-colors">
                        {{ $offre->titre }}
                    </a>
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ $offre->institution->nom }}</p>
                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($offre->description, 150) }}</p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="font-semibold text-primary text-sm">{{ $offre->budgetFormate() }}</p>
                <p class="text-xs text-gray-400 mt-1">Expire le {{ $offre->date_expiration->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-400">{{ $offre->candidatures_count ?? $offre->candidatures->count() }} candidature(s)</span>
            <a href="{{ route('job-offers.show', $offre) }}" class="group inline-flex items-center gap-1 text-sm font-medium text-primary">
                <span class="group-hover:underline">Voir l'offre</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
@empty
    <div class="text-center py-16 text-gray-400">
        <p class="text-lg font-medium text-gray-500">Aucune offre disponible</p>
        <p class="text-sm mt-1">Revenez bientôt ou modifiez vos filtres.</p>
    </div>
@endforelse

{{-- Pagination --}}
<div class="mt-6">
    {{ $offres->links() }}
</div>

@endsection
