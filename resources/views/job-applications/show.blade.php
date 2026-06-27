@extends('layouts.app')

@section('title', $jobApplication->titre_profil)

@section('content')

<div class="mb-6">
    <a href="{{ route('job-applications.index') }}" class="text-sm text-gray-400 hover:text-primary">← Annuaire artisans</a>
</div>

<div class="max-w-2xl">
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-2xl">
                    {{ mb_substr($jobApplication->user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $jobApplication->titre_profil }}</h1>
                    <p class="text-sm text-primary font-medium mt-0.5">{{ $jobApplication->secteur_activite }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $jobApplication->user->name }} · {{ $jobApplication->ville }}</p>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                @if($jobApplication->disponibilite)
                    <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">✓ Disponible</span>
                @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full">Indisponible</span>
                @endif
                @auth
                    @if(auth()->user()->id === $jobApplication->user_id)
                        <a href="{{ route('job-applications.edit', $jobApplication) }}"
                            class="border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                            Modifier mon profil
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Compétences --}}
        <div class="mt-6 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-3">Compétences</p>
            <div class="flex flex-wrap gap-2">
                @foreach($jobApplication->competencesArray() as $comp)
                    <span class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded-full">{{ trim($comp) }}</span>
                @endforeach
            </div>
        </div>

        {{-- Contact --}}
        @auth
            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-2">Contact</p>
                @if($jobApplication->user->telephone)
                    <p class="text-sm text-gray-700">📞 {{ $jobApplication->user->telephone }}</p>
                @endif
                <p class="text-sm text-gray-700 mt-1">📧 {{ $jobApplication->user->email }}</p>
            </div>
        @else
            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-500">
                    <a href="{{ route('login') }}" class="text-primary hover:underline">Connectez-vous</a>
                    pour voir les informations de contact.
                </p>
            </div>
        @endauth
    </div>

    {{-- Voir les offres --}}
    <div class="text-center">
        <a href="{{ route('job-offers.index') }}" class="text-sm text-primary hover:underline">
            Voir les offres d'emploi disponibles →
        </a>
    </div>
</div>

@endsection