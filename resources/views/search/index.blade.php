@extends('layouts.app')

@section('title', $q !== '' ? 'Résultats pour « ' . $q . ' »' : 'Recherche')

@section('content')

<div class="max-w-3xl mx-auto mb-8">
    <h1 class="text-2xl font-bold text-gray-900 text-center">Recherche rapide</h1>
    <p class="text-sm text-gray-500 mt-1 text-center">Offres d'emploi, démarches, artisans et institutions en un seul endroit.</p>

    <form method="GET" action="{{ route('search') }}" class="mt-6 animate-fade-up">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                autofocus
                placeholder="Un emploi, une démarche, un artisan, une institution..."
                class="w-full border border-gray-300 rounded-2xl pl-12 pr-28 py-4 text-sm sm:text-[15px] shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
            >
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-primary-dark transition-all duration-200">
                Rechercher
            </button>
        </div>
    </form>
</div>

@if($q === '')
    <div class="text-center py-16 text-gray-400">
        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        <p class="text-lg font-medium text-gray-500">Tapez un mot-clé pour commencer</p>
        <p class="text-sm mt-1">Par exemple « plombier », « passeport » ou « Lomé ».</p>
    </div>
@elseif($total === 0)
    <div class="text-center py-16 text-gray-400">
        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121zM12 12.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-lg font-medium text-gray-500">Aucun résultat pour « {{ $q }} »</p>
        <p class="text-sm mt-1">Essayez un autre mot-clé ou une orthographe différente.</p>
    </div>
@else
    <div class="max-w-5xl mx-auto space-y-10 stagger">

        {{-- Offres d'emploi --}}
        @if($offres->isNotEmpty())
            <section class="animate-fade-up">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 18.4V14.15m16.5 0c0-1.242-1.357-2.193-2.613-1.841l-1.071.3c-.615.172-1.266.023-1.764-.407l-1.173-1.013a1.875 1.875 0 00-2.456 0l-1.173 1.013c-.498.43-1.149.579-1.764.407l-1.07-.3A1.875 1.875 0 003.75 14.15m16.5 0v1.125c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 15.275V14.15m16.5 0V9.825c0-.622-.504-1.125-1.125-1.125H16.5m-12.75 5.4V9.825c0-.622.504-1.125 1.125-1.125H7.5m9 0V5.625c0-.621-.504-1.125-1.125-1.125H8.625C8.004 4.5 7.5 5.004 7.5 5.625V8.7m9 0H7.5" />
                            </svg>
                        </span>
                        Offres d'emploi
                        <span class="text-gray-400 font-normal">({{ $offres->count() }})</span>
                    </h2>
                    <a href="{{ route('job-offers.index', ['q' => $q]) }}" class="text-xs font-medium text-primary hover:underline">Voir tout</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($offres as $offre)
                        <a href="{{ route('job-offers.show', $offre) }}" class="card-lift bg-white border border-gray-200 rounded-xl p-4 hover:border-primary/40 transition-all duration-200">
                            <p class="font-medium text-gray-900 text-sm">{{ $offre->titre }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $offre->institution->nom }} · {{ $offre->lieu }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Démarches --}}
        @if($procedures->isNotEmpty())
            <section class="animate-fade-up">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M9 12h3.75" />
                            </svg>
                        </span>
                        Démarches
                        <span class="text-gray-400 font-normal">({{ $procedures->count() }})</span>
                    </h2>
                    <a href="{{ route('procedures.index', ['q' => $q]) }}" class="text-xs font-medium text-primary hover:underline">Voir tout</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($procedures as $procedure)
                        <a href="{{ route('procedures.show', $procedure) }}" class="card-lift bg-white border border-gray-200 rounded-xl p-4 hover:border-primary/40 transition-all duration-200">
                            <p class="font-medium text-gray-900 text-sm">{{ $procedure->titre }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $procedure->institution->nom }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Artisans --}}
        @if($artisans->isNotEmpty())
            <section class="animate-fade-up">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83m-3.75 3.75l-2.73-2.73m-2.58 3.71c.057.142.16.257.296.32l3.437 1.611a1 1 0 001.341-.53l1.102-2.45a1 1 0 00-.158-1.077l-4.102-4.103a1 1 0 00-1.077-.158l-2.45 1.102a1 1 0 00-.53 1.341l1.611 3.437z" />
                            </svg>
                        </span>
                        Artisans
                        <span class="text-gray-400 font-normal">({{ $artisans->count() }})</span>
                    </h2>
                    <a href="{{ route('job-applications.index', ['q' => $q]) }}" class="text-xs font-medium text-primary hover:underline">Voir tout</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($artisans as $artisan)
                        <a href="{{ route('job-applications.show', $artisan) }}" class="card-lift bg-white border border-gray-200 rounded-xl p-4 hover:border-primary/40 transition-all duration-200">
                            <p class="font-medium text-gray-900 text-sm">{{ $artisan->titre_profil }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $artisan->user->name }} · {{ $artisan->ville }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Institutions --}}
        @if($institutions->isNotEmpty())
            <section class="animate-fade-up">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18" />
                            </svg>
                        </span>
                        Institutions
                        <span class="text-gray-400 font-normal">({{ $institutions->count() }})</span>
                    </h2>
                    <a href="{{ route('institutions.index', ['q' => $q]) }}" class="text-xs font-medium text-primary hover:underline">Voir tout</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($institutions as $institution)
                        <a href="{{ route('institutions.show', $institution) }}" class="card-lift bg-white border border-gray-200 rounded-xl p-4 hover:border-primary/40 transition-all duration-200">
                            <p class="font-medium text-gray-900 text-sm">{{ $institution->nom }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $institution->ville }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
@endif

@endsection
