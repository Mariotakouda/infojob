@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-lg font-medium text-gray-900 tracking-tight">Bonjour, {{ $user->name }}</h1>
        <p class="text-xs text-gray-400 mt-0.5">Votre espace recruteur / institution</p>
    </div>
    <div class="flex items-center gap-2">
        <button class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 transition-colors" aria-label="Notifications">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
        </button>
    </div>
</div>

{{-- ─── Stats ───────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 mb-6">

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
        </div>
        <p class="text-2xl font-medium text-gray-900 leading-none tracking-tight">{{ $user->institutions->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Institutions</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
        </div>
        <p class="text-2xl font-medium text-blue-700 leading-none tracking-tight">
            {{ $user->institutions->flatMap->procedures->count() }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Démarches</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
        </div>
        <p class="text-2xl font-medium text-green-700 leading-none tracking-tight">
            {{ $user->institutions->flatMap->jobOffers->where('statut', 'publie')->count() }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Offres publiées</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 mb-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
        </div>
        <p class="text-2xl font-medium text-gray-900 leading-none tracking-tight">
            {{ $user->institutions->flatMap->jobOffers->flatMap->candidatures->count() }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Candidatures reçues</p>
    </div>

</div>

{{-- ─── Actions rapides ─────────────────────────────────────────────────── --}}
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('institutions.create') }}"
        class="inline-flex items-center gap-1.5 bg-gray-900 text-white px-3.5 py-2 rounded-lg text-xs font-medium hover:bg-gray-800 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Nouvelle institution
    </a>
    <a href="{{ route('procedures.create') }}"
        class="inline-flex items-center gap-1.5 border border-gray-200 bg-white text-gray-700 px-3.5 py-2 rounded-lg text-xs font-medium hover:bg-gray-50 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Nouvelle démarche
    </a>
    <a href="{{ route('job-offers.create') }}"
        class="inline-flex items-center gap-1.5 border border-gray-200 bg-white text-gray-700 px-3.5 py-2 rounded-lg text-xs font-medium hover:bg-gray-50 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Publier une offre
    </a>
</div>

{{-- ─── Mes institutions ────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-2.5">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-900">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
            Mes institutions
        </div>
        <a href="{{ route('institutions.create') }}" class="group inline-flex items-center gap-1 text-[11px] text-blue-500 hover:text-blue-700 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Ajouter
        </a>
    </div>

    @forelse($user->institutions as $institution)
        @php
            $candidaturesInst = $institution->jobOffers
                ->flatMap->candidatures
                ->where('statut_candidature', 'recue');
            $initials = collect(explode(' ', $institution->nom))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
            $colors = ['bg-blue-50 text-blue-600', 'bg-green-50 text-green-600', 'bg-purple-50 text-purple-600', 'bg-rose-50 text-rose-600'];
            $color = $colors[$loop->index % count($colors)];
        @endphp
        <div class="px-4 py-3 border-b border-gray-50 last:border-0">
            <div class="flex items-start gap-2.5">
                <div class="w-[30px] h-[30px] rounded-lg {{ $color }} flex items-center justify-center text-[11px] font-medium flex-shrink-0 mt-0.5">
                    {{ $initials }}
                </div>
                <div class="flex-1 min-w-0 flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <a href="{{ route('institutions.show', $institution) }}"
                            class="text-xs font-medium text-gray-900 hover:text-blue-600 transition-colors">
                            {{ $institution->nom }}
                        </a>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            {{ $institution->typeLabel() }} · {{ $institution->ville }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3 text-[11px] text-gray-400">
                        <span>{{ $institution->jobOffers->count() }} offre(s)</span>
                        <span>{{ $institution->procedures->count() }} démarche(s)</span>
                        <a href="{{ route('institutions.edit', $institution) }}"
                            class="text-blue-500 hover:text-blue-700">Modifier</a>
                    </div>
                </div>
            </div>

            @if($candidaturesInst->count() > 0)
                <div class="mt-2.5 ml-[42px] space-y-1.5">
                    @foreach($candidaturesInst->take(3) as $cand)
                        <div class="flex items-center justify-between gap-3 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2 flex-wrap">
                            <div>
                                <p class="text-[11px] font-medium text-gray-900">{{ $cand->user->name }}</p>
                                <p class="inline-flex items-center gap-1 text-[11px] text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                    {{ $cand->jobOffer->titre }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('candidatures.update-statut', $cand) }}"
                                class="flex items-center gap-1.5">
                                @csrf @method('PATCH')
                                <select name="statut_candidature"
                                    class="text-[11px] border border-gray-200 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500/20 bg-white">
                                    <option value="en_discussion">En discussion</option>
                                    <option value="acceptee">Accepter</option>
                                    <option value="refusee">Refuser</option>
                                </select>
                                <button class="text-[11px] font-medium px-2 py-1 rounded-md border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-colors">OK</button>
                            </form>
                        </div>
                    @endforeach
                    @if($candidaturesInst->count() > 3)
                        <p class="text-[11px] text-gray-400 ml-1">+{{ $candidaturesInst->count() - 3 }} autre(s)…</p>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="px-4 py-8 text-center">
            <svg class="w-5 h-5 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18" /></svg>
            <p class="text-xs text-gray-400">Aucune institution.</p>
            <a href="{{ route('institutions.create') }}" class="text-xs text-blue-500 hover:text-blue-700">Créer la première</a>
        </div>
    @endforelse
</div>

{{-- ─── Mes démarches ───────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-2.5">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-900">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
            Mes démarches administratives
        </div>
        <a href="{{ route('procedures.create') }}" class="group inline-flex items-center gap-1 text-[11px] text-blue-500 hover:text-blue-700 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Ajouter
        </a>
    </div>

    @php
        $procedures = $user->institutions->flatMap->procedures->sortByDesc('created_at');
    @endphp

    @forelse($procedures->take(10) as $procedure)
        <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-gray-50 last:border-0 flex-wrap">
            <div>
                <a href="{{ route('procedures.show', $procedure) }}"
                    class="text-xs font-medium text-gray-900 hover:text-blue-600 transition-colors">
                    {{ $procedure->titre }}
                </a>
                <p class="text-[11px] text-gray-400 mt-0.5">
                    {{ $procedure->institution->nom }}
                    @if($procedure->cout > 0)
                        · {{ $procedure->coutFormate() }}
                    @else
                        · Gratuit
                    @endif
                    @if($procedure->delai)
                        · {{ $procedure->delai }}
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-1">
                <a href="{{ route('procedures.edit', $procedure) }}"
                    class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-1 rounded-md border border-gray-200 bg-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                    Modifier
                </a>
                <form method="POST" action="{{ route('procedures.destroy', $procedure) }}"
                    onsubmit="return confirm('Supprimer cette démarche ?')">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center justify-center w-[26px] h-[26px] rounded-md border border-red-100 bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="px-4 py-8 text-center">
            <svg class="w-5 h-5 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25" /></svg>
            <p class="text-xs text-gray-400">Aucune démarche publiée.</p>
            <a href="{{ route('procedures.create') }}" class="text-xs text-blue-500 hover:text-blue-700">Créer la première</a>
        </div>
    @endforelse

    @if($procedures->count() > 10)
        <div class="px-4 py-2.5 text-center border-t border-gray-50">
            <p class="text-[11px] text-gray-400">
                {{ $procedures->count() - 10 }} démarche(s) supplémentaire(s) —
                <a href="{{ route('procedures.index') }}" class="text-blue-500 hover:text-blue-700">voir tout</a>
            </p>
        </div>
    @endif
</div>

{{-- ─── Mes offres d'emploi ─────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-900">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
            Mes offres d'emploi
        </div>
        <a href="{{ route('job-offers.create') }}" class="group inline-flex items-center gap-1 text-[11px] text-blue-500 hover:text-blue-700 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform duration-200 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Ajouter
        </a>
    </div>

    @php
        $offres = $user->institutions->flatMap->jobOffers->sortByDesc('created_at');
    @endphp

    @forelse($offres->take(10) as $offre)
        <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-gray-50 last:border-0 flex-wrap">
            <div>
                <a href="{{ route('job-offers.show', $offre) }}"
                    class="text-xs font-medium text-gray-900 hover:text-blue-600 transition-colors">
                    {{ $offre->titre }}
                </a>
                <p class="text-[11px] text-gray-400 mt-0.5">
                    {{ $offre->institution->nom }}
                    · {{ $offre->candidatures->count() }} candidature(s)
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-medium px-2 py-0.5 rounded-full
                    {{ $offre->statut === 'publie' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ $offre->statut === 'publie' ? 'Publiée' : 'En attente' }}
                </span>
                <a href="{{ route('job-offers.edit', $offre) }}"
                    class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-1 rounded-md border border-gray-200 bg-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                    Modifier
                </a>
            </div>
        </div>
    @empty
        <div class="px-4 py-8 text-center">
            <svg class="w-5 h-5 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
            <p class="text-xs text-gray-400">Aucune offre publiée.</p>
            <a href="{{ route('job-offers.create') }}" class="text-xs text-blue-500 hover:text-blue-700">Créer la première</a>
        </div>
    @endforelse

    @if($offres->count() > 10)
        <div class="px-4 py-2.5 text-center border-t border-gray-50">
            <p class="text-[11px] text-gray-400">
                {{ $offres->count() - 10 }} offre(s) supplémentaire(s) —
                <a href="{{ route('job-offers.index') }}" class="text-blue-500 hover:text-blue-700">voir tout</a>
            </p>
        </div>
    @endif
</div>

@endsection