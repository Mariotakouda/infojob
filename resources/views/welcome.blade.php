@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

{{-- ─── Styles CSS requis pour le Slider (Injectés proprement) ─── --}}
@push('styles')
<style>
    @keyframes cssSliderFade {
        0%, 100% { opacity: 0; transform: scale(1.05); }
        5%, 33.33% { opacity: 1; transform: scale(1); }
        38.33% { opacity: 0; transform: scale(1.02); }
    }
    .animate-slider-1 { animation: cssSliderFade 15s infinite 0s ease-in-out; }
    .animate-slider-2 { animation: cssSliderFade 15s infinite 5s ease-in-out; }
    .animate-slider-3 { animation: cssSliderFade 15s infinite 10s ease-in-out; }
</style>
@endpush

{{-- ─── Hero (full bleed, pleine largeur même avec le padding du layout) ─── --}}
<div class="hero-fullbleed relative text-center overflow-hidden bg-gray-900 -mt-6 sm:-mt-8" style="min-height: 520px;">

    {{-- Conteneur des images de fond --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        {{-- Image 1 --}}
        <div class="absolute inset-0 bg-cover bg-center animate-slider-1"
             style="background-image: url('{{ asset('images/img3.jpg') }}');"></div>
        {{-- Image 2 --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-0 animate-slider-2"
             style="background-image: url('{{ asset('images/img1.jpg') }}');"></div>
        {{-- Image 3 --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-0 animate-slider-3"
             style="background-image: url('{{ asset('images/img2.jpg') }}');"></div>
    </div>

    {{-- Overlay dégradé --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/75 z-10 pointer-events-none"></div>

    {{-- Contenu au premier plan --}}
    <div class="relative z-20 flex flex-col items-center justify-center px-4 py-20 sm:py-28">

        <a href="{{ route('home') }}" class="mb-6 transition-transform hover:scale-105 duration-300">
            <img src="{{ asset('images/logo1.png') }}" alt="TravailTogo" class="h-20 drop-shadow-lg">
        </a>

        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight max-w-3xl animate-fade-up"
            style="animation-delay: 0.05s; text-shadow: 0 2px 16px rgba(0,0,0,0.3)">
            Emploi, démarches administratives et profils professionnels<br class="hidden sm:block"> au cœur du Togo
        </h1>

        <p class="text-gray-200 mt-5 max-w-lg mx-auto text-base sm:text-lg leading-relaxed animate-fade-up"
           style="animation-delay: 0.15s">
            InfoJob connecte citoyens, professionnels, recruteurs et institutions sur une seule plateforme.
        </p>

        {{-- Barre de recherche rapide --}}
        <form method="GET" action="{{ route('search') }}"
            class="w-full max-w-xl mt-8 animate-fade-up" style="animation-delay: 0.2s">
            <div class="relative group">
                <div class="absolute inset-0 bg-white rounded-2xl blur-md opacity-20 group-focus-within:opacity-40 transition-opacity duration-300 pointer-events-none"></div>
                <div class="relative flex items-center bg-white rounded-2xl shadow-xl overflow-hidden ring-1 ring-black/5 focus-within:ring-2 focus-within:ring-primary transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 ml-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Un emploi, une démarche, un professionnel, une institution..."
                        class="w-full px-3 py-4 text-sm sm:text-[15px] text-gray-800 placeholder-gray-400 focus:outline-none"
                    >
                    <button type="submit"
                        class="m-1.5 flex-shrink-0 inline-flex items-center gap-1.5 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-primary-dark transition-all duration-200 hover:shadow-md hover:shadow-primary/30">
                        <span class="hidden sm:inline">Rechercher</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Catégories rapides --}}
            <div class="flex flex-wrap items-center justify-center gap-2 mt-3.5">
                <a href="{{ route('job-offers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-white/85 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 px-3 py-1.5 rounded-full transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 18.4V14.15m16.5 0c0-1.242-1.357-2.193-2.613-1.841l-1.071.3c-.615.172-1.266.023-1.764-.407l-1.173-1.013a1.875 1.875 0 00-2.456 0l-1.173 1.013c-.498.43-1.149.579-1.764.407l-1.07-.3A1.875 1.875 0 003.75 14.15m16.5 0v1.125c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 013.75 15.275V14.15m16.5 0V9.825c0-.622-.504-1.125-1.125-1.125H16.5m-12.75 5.4V9.825c0-.622.504-1.125 1.125-1.125H7.5m9 0V5.625c0-.621-.504-1.125-1.125-1.125H8.625C8.004 4.5 7.5 5.004 7.5 5.625V8.7m9 0H7.5" />
                    </svg>
                    Offres d'emploi
                </a>
                <a href="{{ route('procedures.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-white/85 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 px-3 py-1.5 rounded-full transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M9 12h3.75" />
                    </svg>
                    Démarches
                </a>
                <a href="{{ route('job-applications.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-white/85 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 px-3 py-1.5 rounded-full transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83m-3.75 3.75l-2.73-2.73m-2.58 3.71c.057.142.16.257.296.32l3.437 1.611a1 1 0 001.341-.53l1.102-2.45a1 1 0 00-.158-1.077l-4.102-4.103a1 1 0 00-1.077-.158l-2.45 1.102a1 1 0 00-.53 1.341l1.611 3.437z" />
                    </svg>
                    Les professionnels
                </a>
                <a href="{{ route('institutions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-white/85 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 px-3 py-1.5 rounded-full transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18" />
                    </svg>
                    Institutions
                </a>
            </div>
        </form>

        <div class="flex flex-wrap justify-center gap-3 mt-8 animate-fade-up" style="animation-delay: 0.25s">
            <a href="{{ route('job-offers.index') }}"
                class="bg-primary text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-primary-dark transition-all duration-200 hover:shadow-lg hover:shadow-primary/30 hover:-translate-y-0.5">
                Voir les offres d'emploi
            </a>
            @auth
                <a href="{{ route('dashboard') }}"
                    class="border border-white/50 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-white/15 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5">
                    Mon tableau de bord
                </a>
            @else
                <a href="{{ route('register') }}"
                    class="border border-white/50 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-white/15 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5">
                    Créer un compte
                </a>
            @endauth
        </div>

    </div>
</div>

{{-- ─── Liens principaux ───────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-10 mb-16 stagger">

        {{-- Offres d'emploi --}}
        <a href="{{ route('job-offers.index') }}"
            class="card-lift group bg-white border border-gray-100 rounded-2xl p-6 hover:border-primary/40 transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/8 text-primary mb-4 group-hover:bg-primary/15 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 0 1 3.75 18.4V14.15m16.5 0c0-1.242-1.357-2.193-2.613-1.841l-1.071.3c-.615.172-1.266.023-1.764-.407l-1.173-1.013a1.875 1.875 0 0 0-2.456 0l-1.173 1.013c-.498.43-1.149.579-1.764.407l-1.07-.3A1.875 1.875 0 0 0 3.75 14.15m16.5 0v1.125c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 0 1 3.75 15.275V14.15m16.5 0V9.825c0-.622-.504-1.125-1.125-1.125H16.5m-12.75 5.4V9.825c0-.622.504-1.125 1.125-1.125H7.5m9 0V5.625c0-.621-.504-1.125-1.125-1.125H8.625C8.004 4.5 7.5 5.004 7.5 5.625V8.7m9 0H7.5" />
                </svg>
            </div>
            <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200 mb-1">Offres d'emploi</h2>
            <p class="text-sm text-gray-500 leading-relaxed">CDI, CDD, stages et chantiers artisanaux.</p>
        </a>

        {{-- Démarches --}}
        <a href="{{ route('procedures.index') }}"
            class="card-lift group bg-white border border-gray-100 rounded-2xl p-6 hover:border-primary/40 transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/8 text-primary mb-4 group-hover:bg-primary/15 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0 1 12 3m0 0c2.917 0 5.747.294 8.5.862m-21 10.398c0-.652.209-1.285.597-1.816l4.187-5.706A1.5 1.5 0 0 1 5.48 6.11l4.186 5.706a2.25 2.25 0 0 1 .598 1.816m-1.144 3.448h.008v.008H9.14v-.008Z" />
                </svg>
            </div>
            <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200 mb-1">Démarches</h2>
            <p class="text-sm text-gray-500 leading-relaxed">Procédures, coûts, délais et pièces requises.</p>
        </a>

        {{-- Artisans --}}
        <a href="{{ route('job-applications.index') }}"
            class="card-lift group bg-white border border-gray-100 rounded-2xl p-6 hover:border-primary/40 transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/8 text-primary mb-4 group-hover:bg-primary/15 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.83-5.83m-3.75 3.75-2.73-2.73m-2.58 3.71c.057.142.16.257.296.32l3.437 1.611a1 1 0 0 0 1.341-.53l1.102-2.45a1 1 0 0 0-.158-1.077l-4.102-4.103a1 1 0 0 0-1.077-.158l-2.45 1.102a1 1 0 0 0-.53 1.341l1.611 3.437Zm13.23-10.15a3.437 3.437 0 1 1-4.86 4.86 3.437 3.437 0 0 1 4.86-4.86ZM12.75 3.75l-.32 2.25m-6.42 2.25 2.25-.32m-2.25 6.42h2.25" />
                </svg>
            </div>
            <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200 mb-1">Professionnels</h2>
            <p class="text-sm text-gray-500 leading-relaxed">Trouvez un talent disponible près de chez vous.</p>
        </a>

        {{-- Institutions --}}
        <a href="{{ route('institutions.index') }}"
            class="card-lift group bg-white border border-gray-100 rounded-2xl p-6 hover:border-primary/40 transition-all duration-200">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/8 text-primary mb-4 group-hover:bg-primary/15 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                </svg>
            </div>
            <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200 mb-1">Institutions</h2>
            <p class="text-sm text-gray-500 leading-relaxed">Ministères, mairies, préfectures et entreprises.</p>
        </a>

    </div>
</div>

@endsection
