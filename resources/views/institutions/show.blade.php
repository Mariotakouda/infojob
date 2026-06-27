@extends('layouts.app')

@section('title', $institution->nom)

@section('content')

<div class="mb-6">
    <a href="{{ route('institutions.index') }}" class="text-sm text-gray-400 hover:text-primary">← Toutes les institutions</a>
</div>

<div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 text-green-700 mb-2 inline-block">
                {{ $institution->typeLabel() }}
            </span>
            <h1 class="text-2xl font-bold text-gray-900">{{ $institution->nom }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $institution->adresse }} — {{ $institution->ville }}</p>
            @if($institution->contact_public)
                <p class="text-sm text-primary mt-1">📧 {{ $institution->contact_public }}</p>
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
                <a href="{{ route('procedures.create') }}" class="text-sm bg-primary text-white px-3 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">
                    + Ajouter une démarche
                </a>
            @endif
        @endauth
    </div>

    @forelse($institution->procedures as $procedure)
        <a href="{{ route('procedures.show', $procedure) }}"
            class="bg-white border border-gray-200 rounded-xl p-4 mb-3 hover:border-primary transition-colors block">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="font-medium text-gray-900">{{ $procedure->titre }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ Str::limit($procedure->description, 120) }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @if($procedure->cout)
                        <p class="text-sm font-semibold text-primary">{{ $procedure->coutFormate() }}</p>
                    @else
                        <p class="text-sm text-gray-400">Gratuit</p>
                    @endif
                    @if($procedure->delai)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $procedure->delai }}</p>
                    @endif
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
                <a href="{{ route('job-offers.create') }}" class="text-sm bg-primary text-white px-3 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">
                    + Publier une offre
                </a>
            @endif
        @endauth
    </div>

    @forelse($institution->jobOffers as $offre)
        <a href="{{ route('job-offers.show', $offre) }}"
            class="bg-white border border-gray-200 rounded-xl p-4 mb-3 hover:border-primary transition-colors block">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $offre->type_contrat === 'Prestation_Artisanale' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }} mr-2">
                        {{ $offre->typeContratLabel() }}
                    </span>
                    <span class="font-medium text-gray-900">{{ $offre->titre }}</span>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-semibold text-primary">{{ $offre->budgetFormate() }}</p>
                    <p class="text-xs text-gray-400">Expire {{ $offre->date_expiration->format('d/m/Y') }}</p>
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