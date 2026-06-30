@extends('layouts.app')

@section('title', 'Annuaire artisans & talents')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Annuaire artisans & talents</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $profils->total() }} profil(s) disponible(s)</p>
    </div>
    @auth
        @if(auth()->user()->isCitoyen())
            <a href="{{ route('job-applications.create') }}" class="group inline-flex items-center gap-1.5 bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-all duration-200 hover:shadow-md hover:shadow-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Créer mon profil
            </a>
        @endif
    @endauth
</div>

{{-- Filtres --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Compétences, titre..."
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary flex-1 min-w-[180px]">
    <select name="secteur" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">Tous les secteurs</option>
        @foreach($secteurs as $secteur)
            <option value="{{ $secteur }}" {{ request('secteur') === $secteur ? 'selected' : '' }}>{{ $secteur }}</option>
        @endforeach
    </select>
    <input type="text" name="ville" value="{{ request('ville') }}" placeholder="Ville..."
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary w-32">
    <label class="flex items-center gap-2 border border-gray-300 rounded-lg px-3 py-2 text-sm cursor-pointer hover:bg-gray-50">
        <input type="checkbox" name="disponible" value="1" {{ request('disponible') ? 'checked' : '' }} class="rounded border-gray-300 text-primary">
        Disponibles
    </label>
    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">Filtrer</button>
    @if(request()->hasAny(['q','secteur','ville','disponible']))
        <a href="{{ route('job-applications.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">Réinitialiser</a>
    @endif
</form>

{{-- Grille --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($profils as $profil)
        <a href="{{ route('job-applications.show', $profil) }}"
            class="card-lift bg-white border border-gray-200 rounded-xl p-5 hover:border-primary hover:shadow-sm transition-all block animate-fade-up">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-lg">
                    {{ mb_substr($profil->user->name, 0, 1) }}
                </div>
                @if($profil->disponibilite)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Disponible</span>
                @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Indisponible</span>
                @endif
            </div>
            <p class="font-semibold text-gray-900 mb-1">{{ $profil->titre_profil }}</p>
            <p class="text-xs text-primary font-medium mb-2">{{ $profil->secteur_activite }}</p>
            <p class="inline-flex items-center gap-1 text-xs text-gray-500 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                {{ $profil->ville }}
            </p>
            <div class="flex flex-wrap gap-1">
                @foreach(array_slice($profil->competencesArray(), 0, 4) as $comp)
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ trim($comp) }}</span>
                @endforeach
                @if(count($profil->competencesArray()) > 4)
                    <span class="text-xs text-gray-400">+{{ count($profil->competencesArray()) - 4 }}</span>
                @endif
            </div>
        </a>
    @empty
        <div class="col-span-3 text-center py-16 text-gray-400">
            <p class="text-lg font-medium text-gray-500">Aucun profil trouvé</p>
            <p class="text-sm mt-1">Modifiez vos filtres ou revenez plus tard.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $profils->links() }}</div>

@endsection