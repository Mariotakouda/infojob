@extends('layouts.app')

@section('title', 'Tableau de bord recruteur')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ $user->name }} 👋</h1>
    <p class="text-gray-500 text-sm mt-1">Votre espace recruteur</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Institutions</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $user->institutions->count() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Offres publiées</p>
        <p class="text-3xl font-bold text-primary mt-1">
            {{ $user->institutions->flatMap->jobOffers->where('statut', 'publie')->count() }}
        </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Offres en attente</p>
        <p class="text-3xl font-bold text-amber-600 mt-1">
            {{ $user->institutions->flatMap->jobOffers->where('statut', 'en_attente')->count() }}
        </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Candidatures reçues</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">
            {{ $user->institutions->flatMap->jobOffers->flatMap->candidatures->count() }}
        </p>
    </div>
</div>

{{-- Actions rapides --}}
<div class="flex flex-wrap gap-3 mb-8">
    <a href="{{ route('institutions.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">
        + Nouvelle institution
    </a>
    <a href="{{ route('procedures.create') }}" class="border border-primary text-primary px-4 py-2 rounded-lg text-sm hover:bg-primary hover:text-white transition-colors">
        + Nouvelle démarche
    </a>
    <a href="{{ route('job-offers.create') }}" class="border border-primary text-primary px-4 py-2 rounded-lg text-sm hover:bg-primary hover:text-white transition-colors">
        + Publier une offre
    </a>
</div>

{{-- Mes institutions --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <h2 class="font-semibold text-gray-900 mb-4">Mes institutions</h2>

    @forelse($user->institutions as $institution)
        <div class="py-3 border-t border-gray-100 first:border-0">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('institutions.show', $institution) }}" class="font-medium text-sm text-gray-900 hover:text-primary">
                        {{ $institution->nom }}
                    </a>
                    <p class="text-xs text-gray-500">{{ $institution->typeLabel() }} · {{ $institution->ville }}</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span>{{ $institution->jobOffers->count() }} offre(s)</span>
                    <a href="{{ route('institutions.edit', $institution) }}" class="text-primary hover:underline">Modifier</a>
                </div>
            </div>

            {{-- Candidatures de cette institution --}}
            @php
                $candidaturesInst = $institution->jobOffers->flatMap->candidatures->where('statut_candidature', 'recue');
            @endphp
            @if($candidaturesInst->count() > 0)
                <div class="mt-3 ml-4 space-y-2">
                    @foreach($candidaturesInst->take(3) as $cand)
                        <div class="flex items-center justify-between bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                            <div>
                                <p class="text-xs font-medium text-gray-900">{{ $cand->user->name }}</p>
                                <p class="text-xs text-gray-500">→ {{ $cand->jobOffer->titre }}</p>
                            </div>
                            <form method="POST" action="{{ route('candidatures.update-statut', $cand) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <select name="statut_candidature" class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none">
                                    <option value="en_discussion">En discussion</option>
                                    <option value="acceptee">Accepter</option>
                                    <option value="refusee">Refuser</option>
                                </select>
                                <button class="text-xs text-primary hover:underline">OK</button>
                            </form>
                        </div>
                    @endforeach
                    @if($candidaturesInst->count() > 3)
                        <p class="text-xs text-gray-400 ml-3">+{{ $candidaturesInst->count() - 3 }} autre(s)…</p>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <p class="text-sm text-gray-400 py-4 text-center">
            Aucune institution. <a href="{{ route('institutions.create') }}" class="text-primary hover:underline">Créer la première →</a>
        </p>
    @endforelse
</div>

@endsection