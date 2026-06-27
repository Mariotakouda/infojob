@extends('layouts.app')

@section('title', 'Institutions')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Institutions</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $institutions->total() }} institution(s) référencée(s)</p>
    </div>
    @auth
        @if(auth()->user()->isRecruteur())
            <a href="{{ route('institutions.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">
                + Ajouter une institution
            </a>
        @endif
    @endauth
</div>

{{-- Filtres --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher une institution..."
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary flex-1 min-w-[200px]">
    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">Tous les types</option>
        @foreach(['ministere' => 'Ministère','mairie' => 'Mairie','prefecture' => 'Préfecture','direction' => 'Direction','presidence' => 'Présidence','entreprise_privee' => 'Entreprise privée','particulier' => 'Particulier'] as $val => $label)
            <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <input type="text" name="ville" value="{{ request('ville') }}" placeholder="Ville..."
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">Filtrer</button>
    @if(request()->hasAny(['q','type','ville']))
        <a href="{{ route('institutions.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">Réinitialiser</a>
    @endif
</form>

{{-- Grille --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($institutions as $institution)
        <a href="{{ route('institutions.show', $institution) }}"
            class="bg-white border border-gray-200 rounded-xl p-5 hover:border-primary hover:shadow-sm transition-all block">
            <div class="flex items-start justify-between mb-3">
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 text-green-700">
                    {{ $institution->typeLabel() }}
                </span>
                <span class="text-xs text-gray-400">{{ $institution->ville }}</span>
            </div>
            <h2 class="font-semibold text-gray-900 mb-1">{{ $institution->nom }}</h2>
            <p class="text-xs text-gray-500">{{ $institution->adresse }}</p>
            <div class="flex gap-4 mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500">
                <span>{{ $institution->procedures_count }} démarche(s)</span>
                <span>{{ $institution->job_offers_count }} offre(s)</span>
            </div>
        </a>
    @empty
        <div class="col-span-3 text-center py-16 text-gray-400">
            <p class="text-lg font-medium text-gray-500">Aucune institution trouvée</p>
            <p class="text-sm mt-1">Modifiez vos critères de recherche.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $institutions->links() }}</div>

@endsection