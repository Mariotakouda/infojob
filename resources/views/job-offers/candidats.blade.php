@extends('layouts.app')

@section('title', 'Candidats — ' . $jobOffer->titre)

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Tableau de bord
    </a>
</div>

<div class="flex items-start justify-between gap-4 flex-wrap mb-6 animate-fade-up">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Candidats</h1>
        <p class="text-sm text-gray-500 mt-1">
            <a href="{{ route('job-offers.show', $jobOffer) }}" class="hover:text-primary transition-colors">{{ $jobOffer->titre }}</a>
            · {{ $jobOffer->institution->nom }} · {{ $candidatures->count() }} candidature(s)
        </p>
    </div>
    <a href="{{ route('job-offers.candidats.pdf', $jobOffer) }}"
        class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Télécharger la liste en PDF
    </a>
</div>

<div class="space-y-3 stagger">
    @forelse($candidatures as $candidature)
            <div class="card-lift bg-white border border-gray-200 rounded-xl p-5 animate-fade-up">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold text-sm flex-shrink-0">
                            {{ strtoupper(substr($candidature->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $candidature->user->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $candidature->user->email }}</p>
                            <p class="text-xs text-gray-400 mt-1">Candidature envoyée {{ $candidature->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium px-3 py-1 rounded-full
                            {{ match($candidature->statut_candidature) {
                                'acceptee'      => 'bg-green-100 text-green-700',
                                'refusee'       => 'bg-red-100 text-red-700',
                                'en_discussion' => 'bg-blue-100 text-blue-700',
                                default         => 'bg-gray-100 text-gray-600',
                            } }}">
                            {{ $candidature->statutLabel() }}
                        </span>
                    </div>
                </div>

                @if($candidature->note_motivation)
                    <p class="text-sm text-gray-600 mt-3 pt-3 border-t border-gray-100 leading-relaxed">{{ $candidature->note_motivation }}</p>
                @endif

                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between gap-3 flex-wrap">

                    {{-- Documents --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($candidature->aCv())
                            <a href="{{ route('candidatures.download-cv', $candidature) }}"
                                class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                CV
                            </a>
                        @endif
                        @if($candidature->aLettreMotivation())
                            <a href="{{ route('candidatures.download-lettre', $candidature) }}"
                                class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Lettre de motivation
                            </a>
                        @endif
                        @if(! $candidature->aDocuments())
                            <span class="text-xs text-gray-300 italic">Aucun document joint</span>
                        @endif
                    </div>

                    {{-- Statut --}}
                    <form method="POST" action="{{ route('candidatures.update-statut', $candidature) }}" class="flex items-center gap-1.5">
                        @csrf @method('PATCH')
                        <select name="statut_candidature" class="text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white">
                            <option value="en_discussion" {{ $candidature->statut_candidature === 'en_discussion' ? 'selected' : '' }}>En discussion</option>
                            <option value="acceptee" {{ $candidature->statut_candidature === 'acceptee' ? 'selected' : '' }}>Accepter</option>
                            <option value="refusee" {{ $candidature->statut_candidature === 'refusee' ? 'selected' : '' }}>Refuser</option>
                        </select>
                        <button class="text-xs font-medium px-2.5 py-1.5 rounded-md border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                            Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400 bg-white border border-gray-200 rounded-xl">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                <p class="text-lg font-medium text-gray-500">Aucun candidat pour le moment</p>
                <p class="text-sm mt-1">Les candidatures reçues apparaîtront ici.</p>
            </div>
        @endforelse
</div>

@endsection
