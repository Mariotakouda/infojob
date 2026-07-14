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
            @if($jobOffer->affiche)
                <img src="{{ $jobOffer->afficheUrl() }}" alt="Affiche : {{ $jobOffer->titre }}"
                     class="w-full max-h-96 object-cover rounded-xl border border-gray-200">
            @endif
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
                    @if($jobOffer->metier)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Métier / Profession</dt>
                            <dd class="font-medium text-gray-900">{{ $jobOffer->metier }}</dd>
                        </div>
                    @endif
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

            {{-- Accès recruteur : voir tous les candidats --}}
            @auth
                @if(auth()->id() === $jobOffer->institution->user_id)
                    <a href="{{ route('job-offers.candidats', $jobOffer) }}"
                        class="group flex items-center justify-between gap-3 bg-gray-900 text-white rounded-xl p-4 hover:bg-gray-800 transition-all duration-200 hover:-translate-y-0.5">
                        <span class="flex items-center gap-2.5 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            Voir les candidats
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="bg-white/15 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $jobOffer->candidatures->count() }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </a>

                    <a href="{{ route('paiements.boost-form', $jobOffer) }}"
                        class="group flex items-center justify-between gap-3 border border-primary/30 bg-primary/5 text-primary rounded-xl p-4 hover:bg-primary/10 transition-all duration-200 hover:-translate-y-0.5">
                        <span class="flex items-center gap-2.5 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            @if($jobOffer->estActivementBoostee())
                                Prolonger la mise en avant
                            @else
                                Booster cette offre
                            @endif
                        </span>
                        @if($jobOffer->estActivementBoostee())
                            <span class="bg-primary/15 text-xs font-semibold px-2 py-0.5 rounded-full">Active</span>
                        @endif
                    </a>
                @endif
            @endauth

            {{-- Formulaire de candidature --}}
            @auth
                @if(auth()->user()->isCitoyen())
                    @if($dejaPostule)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center animate-fade-up">
                            <div class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-600 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <p class="text-sm text-green-700 font-medium">Candidature envoyée</p>
                            <p class="text-xs text-green-600 mt-1">Vous avez déjà postulé à cette offre</p>
                        </div>
                    @else
                        <div class="bg-white border border-gray-200 rounded-xl p-5">
                            <h3 class="font-semibold text-gray-900 mb-4 text-sm">Postuler à cette offre</h3>
                            <form method="POST" action="{{ route('candidatures.store', $jobOffer) }}" enctype="multipart/form-data">
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
                                    @error('note_motivation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        CV <span class="text-gray-400 font-normal">(optionnel — PDF, DOC, DOCX, 5 Mo max)</span>
                                    </label>
                                    <x-file-input name="cv" />
                                    @error('cv')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Lettre de motivation <span class="text-gray-400 font-normal">(optionnel — PDF, DOC, DOCX, 5 Mo max)</span>
                                    </label>
                                    <x-file-input name="lettre_motivation" />
                                    @error('lettre_motivation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-dark transition-all duration-200 hover:shadow-lg hover:shadow-primary/25">
                                    Envoyer ma candidature
                                </button>
                            </form>
                        </div>
                    @endif
                @endif
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                    <p class="text-sm text-gray-600 mb-3">Connectez-vous pour postuler</p>
                    <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="block w-full bg-primary text-white py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                        Se connecter
                    </a>
                </div>
            @endauth

        </div>
    </div>

</div>
@endsection
