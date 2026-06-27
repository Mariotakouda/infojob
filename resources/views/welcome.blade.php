@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

{{-- Hero --}}
<div class="text-center py-12 sm:py-16">
    <h1 class="text-5xl sm:text-4xl font-bold text-gray-900 leading-tight">
        Emploi, démarches & artisanat au cœur du Togo
    </h1>
    <p class="text-gray-500 mt-4 max-w-xl mx-auto">
        TravailTogo connecte citoyens, artisans, recruteurs et institutions sur une seule plateforme :
        trouvez un emploi, publiez une offre, ou renseignez-vous sur une démarche administrative.
    </p>

    <div class="flex flex-wrap justify-center gap-3 mt-8">
        <a href="{{ route('job-offers.index') }}"
            class="bg-primary text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors">
            Voir les offres d'emploi
        </a>
        @auth
            <a href="{{ route('dashboard') }}"
                class="border border-gray-300 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
                Mon tableau de bord
            </a>
        @else
            <a href="{{ route('register') }}"
                class="border border-gray-300 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
                Créer un compte
            </a>
        @endauth
    </div>
</div>

{{-- Liens principaux --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-12">

    {{-- Offres d'emploi --}}
    <a href="{{ route('job-offers.index') }}"
        class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-primary hover:shadow-sm transition-all">
        <div class="text-primary group-hover:scale-105 transition-transform mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 0 1 3.75 18.4V14.15m16.5 0c0-1.242-1.357-2.193-2.613-1.841l-1.071.3c-.615.172-1.266.023-1.764-.407l-1.173-1.013a1.875 1.875 0 0 0-2.456 0l-1.173 1.013c-.498.43-1.149.579-1.764.407l-1.07-.3A1.875 1.875 0 0 0 3.75 14.15m16.5 0v1.125c0 .621-.504 1.125-1.125 1.125H4.875A1.125 1.125 0 0 1 3.75 15.275V14.15m16.5 0V9.825c0-.622-.504-1.125-1.125-1.125H16.5m-12.75 5.4V9.825c0-.622.504-1.125 1.125-1.125H7.5m9 0V5.625c0-.621-.504-1.125-1.125-1.125H8.625C8.004 4.5 7.5 5.004 7.5 5.625V8.7m9 0H7.5" />
            </svg>
        </div>
        <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors">Offres d'emploi</h2>
        <p class="text-sm text-gray-500 mt-1">CDI, CDD, stages et chantiers artisanaux.</p>
    </a>

    {{-- Démarches --}}
    <a href="{{ route('procedures.index') }}"
        class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-primary hover:shadow-sm transition-all">
        <div class="text-primary group-hover:scale-105 transition-transform mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0 1 12 3m0 0c2.917 0 5.747.294 8.5.862m-21 10.398c0-.652.209-1.285.597-1.816l4.187-5.706A1.5 1.5 0 0 1 5.48 6.11l4.186 5.706a2.25 2.25 0 0 1 .598 1.816m-1.144 3.448h.008v.008H9.14v-.008Z" />
            </svg>
        </div>
        <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors">Démarches</h2>
        <p class="text-sm text-gray-500 mt-1">Procédures, coûts, délais et pièces requises.</p>
    </a>

    {{-- Artisans --}}
    <a href="{{ route('job-applications.index') }}"
        class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-primary hover:shadow-sm transition-all">
        <div class="text-primary group-hover:scale-105 transition-transform mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.83-5.83m-3.75 3.75-2.73-2.73m-2.58 3.71c.057.142.16.257.296.32l3.437 1.611a1 1 0 0 0 1.341-.53l1.102-2.45a1 1 0 0 0-.158-1.077l-4.102-4.103a1 1 0 0 0-1.077-.158l-2.45 1.102a1 1 0 0 0-.53 1.341l1.611 3.437Zm13.23-10.15a3.437 3.437 0 1 1-4.86 4.86 3.437 3.437 0 0 1 4.86-4.86ZM12.75 3.75l-.32 2.25m-6.42 2.25 2.25-.32m-2.25 6.42h2.25" />
            </svg>
        </div>
        <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors">Artisans</h2>
        <p class="text-sm text-gray-500 mt-1">Trouvez un talent disponible près de chez vous.</p>
    </a>

    {{-- Institutions --}}
    <a href="{{ route('institutions.index') }}"
        class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-primary hover:shadow-sm transition-all">
        <div class="text-primary group-hover:scale-105 transition-transform mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
            </svg>
        </div>
        <h2 class="font-semibold text-gray-900 group-hover:text-primary transition-colors">Institutions</h2>
        <p class="text-sm text-gray-500 mt-1">Ministères, mairies, préfectures et entreprises.</p>
    </a>

</div>

@endsection