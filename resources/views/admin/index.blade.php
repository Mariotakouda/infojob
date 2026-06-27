@extends('layouts.app')

@section('title', 'Administration')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Administration</h1>
    <p class="text-gray-500 text-sm mt-1">Panneau de modération & gestion TravailTogo</p>
</div>

{{-- Stats globales --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Utilisateurs</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['users'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Institutions</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['institutions'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Offres publiées</p>
        <p class="text-3xl font-bold text-primary mt-1">{{ $stats['offres_publiees'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-amber-200 bg-amber-50 p-5">
        <p class="text-xs text-amber-600 uppercase tracking-wider font-medium">Offres en attente</p>
        <p class="text-3xl font-bold text-amber-700 mt-1">{{ $stats['offres_attente'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-amber-200 bg-amber-50 p-5">
        <p class="text-xs text-amber-600 uppercase tracking-wider font-medium">Profils en attente</p>
        <p class="text-3xl font-bold text-amber-700 mt-1">{{ $stats['profils_attente'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Candidatures total</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['candidatures'] }}</p>
    </div>
</div>

{{-- Profils artisans à modérer --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
        Profils artisans en attente de modération
        @if($stats['profils_attente'] > 0)
            <span class="bg-amber-100 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full">{{ $stats['profils_attente'] }}</span>
        @endif
    </h2>

    @forelse($profilsEnAttente as $profil)
        <div class="flex items-center justify-between py-3 border-t border-gray-100 first:border-0 flex-wrap gap-3">
            <div>
                <p class="font-medium text-sm text-gray-900">{{ $profil->titre_profil }}</p>
                <p class="text-xs text-gray-500">{{ $profil->user->name }} · {{ $profil->secteur_activite }} · {{ $profil->ville }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $profil->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('job-applications.show', $profil) }}" class="text-xs text-gray-500 border border-gray-300 px-2 py-1 rounded hover:bg-gray-50">
                    Voir
                </a>
                <form method="POST" action="{{ route('admin.moderer-profil', $profil) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut_moderation" value="approuve">
                    <button class="text-xs bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded hover:bg-green-200 transition-colors font-medium">
                        ✓ Approuver
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.moderer-profil', $profil) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut_moderation" value="rejete">
                    <button class="text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded hover:bg-red-100 transition-colors">
                        ✕ Rejeter
                    </button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 py-4 text-center">Aucun profil en attente. ✓</p>
    @endforelse
</div>

{{-- Offres à publier --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
        Offres d'emploi en attente de publication
        @if($stats['offres_attente'] > 0)
            <span class="bg-amber-100 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full">{{ $stats['offres_attente'] }}</span>
        @endif
    </h2>

    @forelse($offresEnAttente as $offre)
        <div class="flex items-center justify-between py-3 border-t border-gray-100 first:border-0 flex-wrap gap-3">
            <div>
                <p class="font-medium text-sm text-gray-900">{{ $offre->titre }}</p>
                <p class="text-xs text-gray-500">{{ $offre->institution->nom }} · {{ $offre->typeContratLabel() }} · {{ $offre->lieu }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $offre->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('job-offers.show', $offre) }}" class="text-xs text-gray-500 border border-gray-300 px-2 py-1 rounded hover:bg-gray-50">
                    Voir
                </a>
                <form method="POST" action="{{ route('admin.publier-offre', $offre) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="publie">
                    <button class="text-xs bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded hover:bg-green-200 transition-colors font-medium">
                        ✓ Publier
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.publier-offre', $offre) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="statut" value="expire">
                    <button class="text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded hover:bg-red-100 transition-colors">
                        ✕ Rejeter
                    </button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 py-4 text-center">Aucune offre en attente. ✓</p>
    @endforelse
</div>

{{-- Derniers utilisateurs --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h2 class="font-semibold text-gray-900 mb-4">Derniers inscrits</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    <th class="text-left py-2 pr-4">Nom</th>
                    <th class="text-left py-2 pr-4">Email</th>
                    <th class="text-left py-2 pr-4">Rôle</th>
                    <th class="text-left py-2 pr-4">Inscrit</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($derniersUsers as $user)
                    <tr class="border-t border-gray-50">
                        <td class="py-3 pr-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="py-3 pr-4 text-gray-500">{{ $user->email }}</td>
                        <td class="py-3 pr-4">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'recruteur' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-3 pr-4 text-gray-400 text-xs">{{ $user->created_at->diffForHumans() }}</td>
                        <td class="py-3">
                            @if(!$user->isAdmin())
                                <form method="POST" action="{{ route('admin.supprimer-user', $user) }}"
                                    onsubmit="return confirm('Supprimer {{ $user->name }} et toutes ses données ?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-400 hover:text-red-600 transition-colors">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection