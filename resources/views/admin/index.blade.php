@extends('layouts.app')

@section('title', 'Administration')

@section('content')

{{-- Topbar --}}
<div class="flex items-center justify-between mb-8 pl-4">
    <div>
        <h1 class="text-lg font-medium text-gray-900 tracking-tight">Administration</h1>
        <p class="text-xs text-gray-400 mt-0.5">TravailTogo · Panneau de modération</p>
    </div>
    <div class="flex items-center gap-2">
        <button class="relative w-8 h-8 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 transition-colors" aria-label="Notifications">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            @if($stats['offres_attente'] + $stats['profils_attente'] > 0)
                <span class="absolute top-1 right-1 w-1.5 h-1.5 rounded-full bg-red-500 border border-white"></span>
            @endif
        </button>
        <button class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 transition-colors" aria-label="Paramètres">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>
    </div>
</div>

{{-- KPIs --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-2.5 mb-6">

    <div class="bg-white rounded-xl border border-gray-200 p-4 relative overflow-hidden">
        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
        </div>
        <p class="text-2xl font-medium text-gray-900 leading-none tracking-tight">{{ $stats['users'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Utilisateurs</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 relative overflow-hidden">
        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
        </div>
        <p class="text-2xl font-medium text-green-700 leading-none tracking-tight">{{ $stats['offres_publiees'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Offres publiées</p>
    </div>

    <div class="bg-white rounded-xl border border-amber-100 bg-amber-50 p-4 relative overflow-hidden">
        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <p class="text-2xl font-medium text-amber-700 leading-none tracking-tight">{{ $stats['offres_attente'] + $stats['profils_attente'] }}</p>
        <p class="text-xs text-amber-500 mt-1">En attente</p>
        @if($stats['offres_attente'] + $stats['profils_attente'] > 0)
            <span class="absolute top-3 right-3 text-[10px] font-medium px-1.5 py-0.5 rounded-full bg-amber-200 text-amber-700">urgent</span>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
        </div>
        <p class="text-2xl font-medium text-gray-900 leading-none tracking-tight">{{ $stats['institutions'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Institutions</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
        </div>
        <p class="text-2xl font-medium text-gray-900 leading-none tracking-tight">{{ $stats['candidatures'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Candidatures</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
        </div>
        <p class="text-2xl font-medium text-gray-900 leading-none tracking-tight">{{ $stats['offres_publiees'] > 0 ? round(($stats['candidatures'] / $stats['offres_publiees'])) : 0 }}</p>
        <p class="text-xs text-gray-400 mt-1">Candidatures / offre</p>
    </div>

</div>

{{-- Modération : deux colonnes --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-2.5 mb-2.5">

    {{-- Profils artisans --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <div class="flex items-center gap-1.5 text-sm font-medium text-gray-900">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                Profils à valider
                @if($stats['profils_attente'] > 0)
                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-medium bg-amber-100 text-amber-700">{{ $stats['profils_attente'] }}</span>
                @endif
            </div>
            <a href="#" class="text-[11px] text-blue-500 hover:text-blue-700">Voir tout</a>
        </div>

        @forelse($profilsEnAttente as $profil)
            @php
                $initials = collect(explode(' ', $profil->user->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                $colors = ['bg-blue-50 text-blue-600', 'bg-green-50 text-green-600', 'bg-purple-50 text-purple-600', 'bg-rose-50 text-rose-600'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            <div class="flex items-start gap-2.5 px-4 py-3 border-b border-gray-50 last:border-0">
                <div class="w-[30px] h-[30px] rounded-lg {{ $color }} flex items-center justify-center text-[11px] font-medium flex-shrink-0 mt-0.5">
                    {{ $initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-900 truncate">{{ $profil->titre_profil }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $profil->user->name }} · {{ $profil->secteur_activite }} · {{ $profil->ville }}</p>
                    <div class="flex gap-1 mt-1.5">
                        <a href="{{ route('job-applications.show', $profil) }}" class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-1 rounded-md border border-gray-200 bg-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Voir
                        </a>
                        <form method="POST" action="{{ route('admin.moderer-profil', $profil) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut_moderation" value="approuve">
                            <button class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-1 rounded-md border border-green-200 bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Approuver
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.moderer-profil', $profil) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut_moderation" value="rejete">
                            <button class="inline-flex items-center justify-center w-[26px] h-[26px] rounded-md border border-red-100 bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-4 py-8 text-center">
                <svg class="w-5 h-5 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-xs text-gray-400">Aucun profil en attente.</p>
            </div>
        @endforelse
    </div>

    {{-- Offres d'emploi --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <div class="flex items-center gap-1.5 text-sm font-medium text-gray-900">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                Offres à publier
                @if($stats['offres_attente'] > 0)
                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-medium bg-amber-100 text-amber-700">{{ $stats['offres_attente'] }}</span>
                @endif
            </div>
            <a href="#" class="text-[11px] text-blue-500 hover:text-blue-700">Voir tout</a>
        </div>

        @forelse($offresEnAttente as $offre)
            @php
                $initials = strtoupper(substr($offre->institution->nom, 0, 2));
                $colors = ['bg-blue-50 text-blue-600', 'bg-green-50 text-green-600', 'bg-purple-50 text-purple-600', 'bg-rose-50 text-rose-600'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            <div class="flex items-start gap-2.5 px-4 py-3 border-b border-gray-50 last:border-0">
                <div class="w-[30px] h-[30px] rounded-lg {{ $color }} flex items-center justify-center text-[11px] font-medium flex-shrink-0 mt-0.5">
                    {{ $initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-900 truncate">{{ $offre->titre }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $offre->institution->nom }} · {{ $offre->typeContratLabel() }} · {{ $offre->lieu }}</p>
                    <div class="flex gap-1 mt-1.5">
                        <a href="{{ route('job-offers.show', $offre) }}" class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-1 rounded-md border border-gray-200 bg-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Voir
                        </a>
                        <form method="POST" action="{{ route('admin.publier-offre', $offre) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="publie">
                            <button class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-1 rounded-md border border-green-200 bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Publier
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.publier-offre', $offre) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="statut" value="expire">
                            <button class="inline-flex items-center justify-center w-[26px] h-[26px] rounded-md border border-red-100 bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-4 py-8 text-center">
                <svg class="w-5 h-5 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-xs text-gray-400">Aucune offre en attente.</p>
            </div>
        @endforelse
    </div>

</div>

{{-- Gestion des utilisateurs --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden" id="users-panel">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
            <span class="text-sm font-medium text-gray-900">Utilisateurs</span>
            <span class="text-xs text-gray-400">{{ $stats['users'] }} compte{{ $stats['users'] > 1 ? 's' : '' }}</span>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            <div class="relative">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input
                    type="text"
                    id="users-search"
                    placeholder="Rechercher…"
                    class="pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-colors w-full sm:w-48 bg-gray-50"
                    autocomplete="off">
            </div>
            <div class="flex items-center gap-0.5 bg-gray-100 border border-gray-200 rounded-lg p-1 text-xs font-medium" id="role-filter" role="tablist">
                <button type="button" data-role="" class="role-filter-btn px-2.5 py-1 rounded-md transition-colors text-gray-900 bg-white shadow-sm text-[11px]" aria-pressed="true">Tous</button>
                <button type="button" data-role="admin" class="role-filter-btn px-2.5 py-1 rounded-md transition-colors text-gray-500 hover:text-gray-900 text-[11px]" aria-pressed="false">Admins</button>
                <button type="button" data-role="recruteur" class="role-filter-btn px-2.5 py-1 rounded-md transition-colors text-gray-500 hover:text-gray-900 text-[11px]" aria-pressed="false">Recruteurs</button>
                <button type="button" data-role="citoyen" class="role-filter-btn px-2.5 py-1 rounded-md transition-colors text-gray-500 hover:text-gray-900 text-[11px]" aria-pressed="false">Citoyens</button>
            </div>
        </div>
    </div>

    <div id="users-table-container" class="relative min-h-[120px]">
        <div class="flex items-center justify-center py-10 text-xs text-gray-400 gap-2">
            <svg class="w-4 h-4 animate-spin text-gray-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            Chargement…
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const container   = document.getElementById('users-table-container');
    const searchInput = document.getElementById('users-search');
    const roleButtons = document.querySelectorAll('.role-filter-btn');
    const usersUrl    = @json(route('admin.users'));

    let currentRole = '';
    let debounceTimer = null;
    let activeRequest = null;

    function setActiveRoleButton(role) {
        roleButtons.forEach((btn) => {
            const isActive = btn.dataset.role === role;
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            btn.classList.toggle('bg-white', isActive);
            btn.classList.toggle('shadow-sm', isActive);
            btn.classList.toggle('text-gray-900', isActive);
            btn.classList.toggle('text-gray-500', !isActive);
        });
    }

    async function loadUsers(page = 1) {
        const params = new URLSearchParams();
        if (searchInput.value.trim() !== '') params.set('search', searchInput.value.trim());
        if (currentRole !== '') params.set('role', currentRole);
        if (page > 1) params.set('page', page);

        if (activeRequest) activeRequest.abort();
        const controller = new AbortController();
        activeRequest = controller;

        container.style.opacity = '0.5';

        try {
            const response = await fetch(`${usersUrl}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: controller.signal,
            });
            if (!response.ok) throw new Error('Erreur réseau');
            container.innerHTML = await response.text();
        } catch (error) {
            if (error.name !== 'AbortError') {
                container.innerHTML = '<p class="text-xs text-red-400 text-center py-10">Impossible de charger les utilisateurs. Réessayez.</p>';
            }
        } finally {
            container.style.opacity = '1';
        }
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => loadUsers(1), 350);
    });

    roleButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            currentRole = btn.dataset.role;
            setActiveRoleButton(currentRole);
            loadUsers(1);
        });
    });

    container.addEventListener('click', (event) => {
        const pageLink = event.target.closest('nav a');
        if (pageLink) {
            event.preventDefault();
            const url = new URL(pageLink.href);
            loadUsers(url.searchParams.get('page') || 1);
        }
    });

    container.addEventListener('submit', (event) => {
        const form = event.target.closest('.js-delete-user-form');
        if (!form) return;
        const name = form.dataset.userName || 'cet utilisateur';
        if (!confirm(`Supprimer ${name} et toutes ses données ? Cette action est irréversible.`)) {
            event.preventDefault();
        }
    });

    loadUsers(1);
})();
</script>
@endpush

@endsection