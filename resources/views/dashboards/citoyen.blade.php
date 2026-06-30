@extends('layouts.app')

@section('title', 'Mon tableau de bord')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ $user->name }}</h1>
    <p class="text-gray-500 text-sm mt-1">Votre espace citoyen / artisan</p>
</div>

{{-- ─── Stats ───────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Mes profils</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $user->jobApplications->count() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Candidatures envoyées</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $user->candidatures->count() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Candidatures acceptées</p>
        <p class="text-3xl font-bold text-green-600 mt-1">
            {{ $user->candidatures->where('statut_candidature', 'acceptee')->count() }}
        </p>
    </div>
</div>

{{-- ─── Raccourcis rapides ──────────────────────────────────────────────── --}}
<div class="flex flex-wrap gap-3 mb-8">
    <a href="{{ route('job-offers.index') }}"
        class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">
        Voir les offres d'emploi
    </a>
    <a href="{{ route('job-applications.create') }}"
        class="border border-primary text-primary px-4 py-2 rounded-lg text-sm hover:bg-primary hover:text-white transition-colors">
        + Créer un profil artisan
    </a>
    <a href="{{ route('procedures.index') }}"
        class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
        Consulter les démarches
    </a>
</div>

{{-- ─── Mes profils / vitrines ─────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-gray-900">Mes profils / vitrines artisan</h2>
        <a href="{{ route('job-applications.create') }}"
            class="text-sm bg-primary text-white px-4 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">
            + Ajouter
        </a>
    </div>

    @forelse($user->jobApplications as $profil)
        <div class="flex items-center justify-between py-3 border-t border-gray-100 first:border-0">
            <div>
                <a href="{{ route('job-applications.show', $profil) }}"
                    class="font-medium text-sm text-gray-900 hover:text-primary transition-colors">
                    {{ $profil->titre_profil }}
                </a>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $profil->secteur_activite }} · {{ $profil->ville }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $profil->statut_moderation === 'approuve'
                        ? 'bg-green-100 text-green-700'
                        : ($profil->statut_moderation === 'rejete'
                            ? 'bg-red-100 text-red-700'
                            : 'bg-yellow-100 text-yellow-700') }}">
                    {{ $profil->statutLabel() }}
                </span>
                <a href="{{ route('job-applications.edit', $profil) }}"
                    class="text-xs text-primary hover:underline">Modifier</a>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 py-4 text-center">
            Aucun profil créé.
            <a href="{{ route('job-applications.create') }}" class="group inline-flex items-center gap-1 text-primary">
                <span class="group-hover:underline">Créer votre vitrine</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </p>
    @endforelse
</div>

{{-- ─── Mes candidatures ────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-gray-900">Mes candidatures</h2>
        <a href="{{ route('candidatures.index') }}" class="group inline-flex items-center gap-1 text-xs text-primary">
            <span class="group-hover:underline">Voir tout</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>

    @forelse($user->candidatures->take(5) as $candidature)
        <div class="flex items-center justify-between py-3 border-t border-gray-100 first:border-0">
            <div>
                <a href="{{ route('job-offers.show', $candidature->jobOffer) }}"
                    class="font-medium text-sm text-gray-900 hover:text-primary transition-colors">
                    {{ $candidature->jobOffer->titre }}
                </a>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $candidature->jobOffer->institution->nom }}
                    · {{ $candidature->created_at->diffForHumans() }}
                </p>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full
                {{ match($candidature->statut_candidature) {
                    'acceptee'      => 'bg-green-100 text-green-700',
                    'refusee'       => 'bg-red-100 text-red-700',
                    'en_discussion' => 'bg-blue-100 text-blue-700',
                    default         => 'bg-gray-100 text-gray-600',
                } }}">
                {{ $candidature->statutLabel() }}
            </span>
        </div>
    @empty
        <p class="text-sm text-gray-400 py-4 text-center">
            Aucune candidature.
            <a href="{{ route('job-offers.index') }}" class="group inline-flex items-center gap-1 text-primary">
                <span class="group-hover:underline">Voir les offres</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </p>
    @endforelse

    @if($user->candidatures->count() > 5)
        <div class="mt-3 text-center">
            <a href="{{ route('candidatures.index') }}" class="group inline-flex items-center gap-1 text-xs text-primary">
                <span class="group-hover:underline">Voir toutes les candidatures ({{ $user->candidatures->count() }})</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    @endif
</div>

@endsection