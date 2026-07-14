@extends('layouts.app')

@section('title', $jobApplication->titre_profil)

@section('content')

<div class="mb-6">
    <a href="{{ route('job-applications.index') }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Catalogue des métiers
    </a>
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
                    <span class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Disponible
                    </span>
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
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-3">Contact</p>

                @if($jobApplication->user->telephone)
                    <p class="inline-flex items-center gap-1.5 text-sm text-gray-700 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        {{ $jobApplication->user->telephone }}
                    </p>
                @endif
                <p class="inline-flex items-center gap-1.5 text-sm text-gray-700 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    {{ $jobApplication->user->email }}
                </p>

                {{-- Actions de contact direct --}}
                <div class="flex flex-wrap gap-2">
                    @if($jobApplication->user->telephone)
                        @php
                            $telClean = preg_replace('/[^\d+]/', '', $jobApplication->user->telephone);
                            $waNumber = preg_replace('/\D/', '', $jobApplication->user->telephone);
                        @endphp

                        <a href="tel:{{ $telClean }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-2 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            Appeler
                        </a>

                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-2 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M12.001 2C6.478 2 2 6.478 2 12c0 1.85.499 3.622 1.446 5.185L2 22l4.925-1.418A9.955 9.955 0 0012.001 22C17.523 22 22 17.523 22 12S17.523 2 12.001 2zm0 18.164c-1.732 0-3.379-.5-4.788-1.446l-.343-.203-2.918.84.86-2.847-.222-.362A8.145 8.145 0 013.837 12c0-4.508 3.656-8.164 8.164-8.164S20.164 7.492 20.164 12 16.508 20.164 12.001 20.164z"/>
                            </svg>
                            WhatsApp
                        </a>
                    @endif

                    <a href="mailto:{{ $jobApplication->user->email }}"
                       class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        Email
                    </a>
                </div>
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
        <a href="{{ route('job-offers.index') }}" class="group inline-flex items-center gap-1 text-sm text-primary">
            <span class="group-hover:underline">Voir les offres d'emploi disponibles</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</div>

@endsection
