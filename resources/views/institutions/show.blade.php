@extends('layouts.app')

@section('title', $institution->nom)

@section('content')

<div class="mb-6">
    <a href="{{ route('institutions.index') }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Toutes les institutions
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 animate-fade-up">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 text-green-700 mb-2 inline-block">
                {{ $institution->typeLabel() }}
            </span>
            <h1 class="text-2xl font-bold text-gray-900">{{ $institution->nom }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $institution->adresse }} — {{ $institution->ville }}</p>
            @if($institution->contact_public)
                <p class="inline-flex items-center gap-1.5 text-sm text-primary mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    {{ $institution->contact_public }}
                </p>
            @endif
        </div>
        @auth
            @if(auth()->user()->id === $institution->user_id || auth()->user()->isAdmin())
                <div class="flex gap-2">
                    <a href="{{ route('institutions.edit', $institution) }}"
                        class="border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('institutions.destroy', $institution) }}"
                        onsubmit="return confirm('Supprimer cette institution et toutes ses données ?')">
                        @csrf @method('DELETE')
                        <button class="border border-red-200 text-red-600 px-3 py-1.5 rounded-lg text-sm hover:bg-red-50 transition-colors">
                            Supprimer
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>

{{-- Démarches --}}
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Démarches administratives</h2>
        @auth
            @if(auth()->user()->id === $institution->user_id)
                <a href="{{ route('procedures.create') }}" class="group inline-flex items-center gap-1.5 text-sm bg-primary text-white px-3 py-1.5 rounded-lg hover:bg-primary-dark transition-all duration-200 hover:shadow-md hover:shadow-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Ajouter une démarche
                </a>
            @endif
        @endauth
    </div>

    @forelse($institution->procedures as $procedure)
        <a href="{{ route('procedures.show', $procedure) }}"
            class="group card-lift bg-white border border-gray-200 rounded-xl p-4 mb-3 hover:border-primary transition-colors block">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="font-medium text-gray-900">{{ $procedure->titre }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ Str::limit($procedure->description, 120) }}</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="text-right">
                        @if($procedure->cout)
                            <p class="text-sm font-semibold text-primary">{{ $procedure->coutFormate() }}</p>
                        @else
                            <p class="text-sm text-gray-400">Gratuit</p>
                        @endif
                        @if($procedure->delai)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $procedure->delai }}</p>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-primary transition-all duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">{{ $procedure->requirements->count() }} pièce(s) requise(s)</p>
        </a>
    @empty
        <p class="text-sm text-gray-400 bg-white border border-gray-200 rounded-xl p-6 text-center">
            Aucune démarche publiée pour l'instant.
        </p>
    @endforelse
</div>

{{-- Offres d'emploi --}}
<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Offres d'emploi & chantiers</h2>
        @auth
            @if(auth()->user()->id === $institution->user_id)
                <a href="{{ route('job-offers.create') }}" class="group inline-flex items-center gap-1.5 text-sm bg-primary text-white px-3 py-1.5 rounded-lg hover:bg-primary-dark transition-all duration-200 hover:shadow-md hover:shadow-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Publier une offre
                </a>
            @endif
        @endauth
    </div>

    @forelse($institution->jobOffers as $offre)
        <a href="{{ route('job-offers.show', $offre) }}"
            class="group card-lift bg-white border border-gray-200 rounded-xl p-4 mb-3 hover:border-primary transition-colors block">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $offre->type_contrat === 'Prestation_Artisanale' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }} mr-2">
                        {{ $offre->typeContratLabel() }}
                    </span>
                    <span class="font-medium text-gray-900">{{ $offre->titre }}</span>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-primary">{{ $offre->budgetFormate() }}</p>
                        <p class="text-xs text-gray-400">Expire {{ $offre->date_expiration->format('d/m/Y') }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-primary transition-all duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
        </a>
    @empty
        <p class="text-sm text-gray-400 bg-white border border-gray-200 rounded-xl p-6 text-center">
            Aucune offre active en ce moment.
        </p>
    @endforelse
</div>

@endsection