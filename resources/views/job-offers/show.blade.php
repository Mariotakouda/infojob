@extends('layouts.app')

@section('title', $jobOffer->titre)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Fil d'ariane --}}
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ route('job-offers.index') }}" class="hover:text-primary">Offres</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">{{ $jobOffer->titre }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Contenu principal --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                {{ $jobOffer->type_contrat === 'Prestation_Artisanale' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $jobOffer->typeContratLabel() }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $jobOffer->lieu }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $jobOffer->titre }}</h1>
                        <p class="text-gray-500 mt-1">
                            <a href="{{ route('institutions.show', $jobOffer->institution) }}" class="hover:text-primary">
                                {{ $jobOffer->institution->nom }}
                            </a>
                        </p>
                    </div>
                    @if($jobOffer->budget_salaire)
                        <div class="text-right flex-shrink-0">
                            <p class="text-xl font-bold text-primary">{{ $jobOffer->budgetFormate() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h2 class="font-semibold text-gray-900 mb-4">Description du poste / chantier</h2>
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $jobOffer->description }}
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Infos clés --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold text-gray-900 mb-4 text-sm">Informations</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Type</dt>
                        <dd class="font-medium text-gray-900">{{ $jobOffer->typeContratLabel() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Lieu</dt>
                        <dd class="font-medium text-gray-900">{{ $jobOffer->lieu }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Expire le</dt>
                        <dd class="font-medium text-gray-900">{{ $jobOffer->date_expiration->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Candidatures</dt>
                        <dd class="font-medium text-gray-900">{{ $jobOffer->candidatures->count() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Formulaire de candidature --}}
            @auth
                @if(auth()->user()->isCitoyen())
                    @if($dejaPostule)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                            <p class="text-sm text-green-700 font-medium">✓ Candidature envoyée</p>
                            <p class="text-xs text-green-600 mt-1">Vous avez déjà postulé à cette offre</p>
                        </div>
                    @else
                        <div class="bg-white border border-gray-200 rounded-xl p-5">
                            <h3 class="font-semibold text-gray-900 mb-4 text-sm">Postuler à cette offre</h3>
                            <form method="POST" action="{{ route('candidatures.store', $jobOffer) }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="note_motivation" class="block text-xs font-medium text-gray-700 mb-1">
                                        Message / Estimation de prix
                                        <span class="text-gray-400 font-normal">(optionnel)</span>
                                    </label>
                                    <textarea
                                        id="note_motivation"
                                        name="note_motivation"
                                        rows="4"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                        placeholder="Présentez-vous, donnez votre estimation de prix pour les travaux..."
                                    >{{ old('note_motivation') }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors">
                                    Envoyer ma candidature
                                </button>
                            </form>
                        </div>
                    @endif
                @endif
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                    <p class="text-sm text-gray-600 mb-3">Connectez-vous pour postuler</p>
                    <a href="{{ route('login') }}" class="block w-full bg-primary text-white py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                        Se connecter
                    </a>
                </div>
            @endauth

        </div>
    </div>

</div>
@endsection
