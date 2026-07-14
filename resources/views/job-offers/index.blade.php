@extends('layouts.app')

@section('title', 'Offres d\'emploi')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Offres d'emploi & chantiers</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $offres->total() }} offre(s) disponible(s)
            @if(request('q'))
                pour « {{ request('q') }} »
                <a href="{{ route('job-offers.index') }}" class="text-primary hover:underline ml-1">(effacer)</a>
            @endif
        </p>
    </div>
</div>

{{-- Filtres --}}
<form method="GET" class="flex flex-wrap items-center gap-3 mb-6 bg-white border border-gray-200 rounded-xl p-3.5 animate-fade-up">
    @if(request('q'))
        <input type="hidden" name="q" value="{{ request('q') }}">
    @endif
    <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 18.4V14.15m16.5 0c0-1.242-1.357-2.193-2.613-1.841l-1.071.3c-.615.172-1.266.023-1.764-.407l-1.173-1.013a1.875 1.875 0 00-2.456 0l-1.173 1.013c-.498.43-1.149.579-1.764.407l-1.07-.3A1.875 1.875 0 003.75 14.15m16.5 0v1.125c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 15.275V14.15m16.5 0V9.825c0-.622-.504-1.125-1.125-1.125H16.5m-12.75 5.4V9.825c0-.622.504-1.125 1.125-1.125H7.5m9 0V5.625c0-.621-.504-1.125-1.125-1.125H8.625C8.004 4.5 7.5 5.004 7.5 5.625V8.7m9 0H7.5" />
        </svg>
        <select name="type" class="border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary appearance-none bg-white">
            <option value="">Tous les types</option>
            <option value="CDI" {{ request('type') === 'CDI' ? 'selected' : '' }}>CDI</option>
            <option value="CDD" {{ request('type') === 'CDD' ? 'selected' : '' }}>CDD</option>
            <option value="Stage" {{ request('type') === 'Stage' ? 'selected' : '' }}>Stage</option>
            <option value="Prestation_Artisanale" {{ request('type') === 'Prestation_Artisanale' ? 'selected' : '' }}>Prestation artisanale</option>
        </select>
    </div>

    <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83m-3.75 3.75l-2.73-2.73m-2.58 3.71c.057.142.16.257.296.32l3.437 1.611a1 1 0 001.341-.53l1.102-2.45a1 1 0 00-.158-1.077l-4.102-4.103a1 1 0 00-1.077-.158l-2.45 1.102a1 1 0 00-.53 1.341l1.611 3.437zM13.5 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
        </svg>
        <select name="metier" class="border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary appearance-none bg-white min-w-[200px]">
            <option value="">Tous les métiers / professions</option>
            @foreach($metiers as $metier)
                <option value="{{ $metier }}" {{ request('metier') === $metier ? 'selected' : '' }}>{{ $metier }}</option>
            @endforeach
        </select>
    </div>

    <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
        </svg>
        <input
            type="text"
            name="lieu"
            value="{{ request('lieu') }}"
            placeholder="Filtrer par ville..."
            class="border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        >
    </div>

    <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-all duration-200 hover:shadow-md hover:shadow-primary/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        Filtrer
    </button>
    @if(request()->hasAny(['type', 'metier', 'lieu', 'q']))
        <a href="{{ route('job-offers.index') }}" class="inline-flex items-center gap-1.5 border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
            </svg>
            Réinitialiser
        </a>
    @endif
</form>

{{-- Grille d'offres --}}
@forelse($offres as $offre)
    <div class="card-lift bg-white border border-gray-200 rounded-xl p-5 mb-4 hover:border-primary transition-colors animate-fade-up">
        <div class="flex items-start justify-between gap-4">
            @if($offre->affiche)
                <img src="{{ $offre->afficheUrl() }}" alt="" class="hidden sm:block w-16 h-16 object-cover rounded-lg border border-gray-200 flex-shrink-0">
            @endif
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $offre->type_contrat === 'Prestation_Artisanale' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $offre->typeContratLabel() }}
                    </span>
                    @if($offre->metier)
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-primary/10 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 18.4V14.15m16.5 0c0-1.242-1.357-2.193-2.613-1.841l-1.071.3c-.615.172-1.266.023-1.764-.407l-1.173-1.013a1.875 1.875 0 00-2.456 0l-1.173 1.013c-.498.43-1.149.579-1.764.407l-1.07-.3A1.875 1.875 0 003.75 14.15m16.5 0v1.125c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 15.275V14.15m16.5 0V9.825c0-.622-.504-1.125-1.125-1.125H16.5m-12.75 5.4V9.825c0-.622.504-1.125 1.125-1.125H7.5m9 0V5.625c0-.621-.504-1.125-1.125-1.125H8.625C8.004 4.5 7.5 5.004 7.5 5.625V8.7m9 0H7.5" />
                            </svg>
                            {{ $offre->metier }}
                        </span>
                    @endif
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
