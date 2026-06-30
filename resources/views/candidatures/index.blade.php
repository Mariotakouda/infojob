@extends('layouts.app')

@section('title', 'Mes candidatures')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mes candidatures</h1>
    <p class="text-sm text-gray-500 mt-1">{{ $candidatures->total() }} candidature(s)</p>
</div>

@forelse($candidatures as $candidature)
    <div class="card-lift bg-white border border-gray-200 rounded-xl p-5 mb-4 animate-fade-up">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h2 class="font-semibold text-gray-900">
                    <a href="{{ route('job-offers.show', $candidature->jobOffer) }}" class="hover:text-primary transition-colors">
                        {{ $candidature->jobOffer->titre }}
                    </a>
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $candidature->jobOffer->institution->nom }} · {{ $candidature->jobOffer->institution->ville }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Envoyée {{ $candidature->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="text-xs font-medium px-3 py-1 rounded-full
                    {{ match($candidature->statut_candidature) {
                        'acceptee'      => 'bg-green-100 text-green-700',
                        'refusee'       => 'bg-red-100 text-red-700',
                        'en_discussion' => 'bg-blue-100 text-blue-700',
                        default         => 'bg-gray-100 text-gray-600',
                    } }}">
                    {{ $candidature->statutLabel() }}
                </span>
                @if($candidature->statut_candidature === 'recue')
                    <form method="POST" action="{{ route('candidatures.destroy', $candidature) }}"
                        onsubmit="return confirm('Retirer cette candidature ?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-gray-400 hover:text-red-500 transition-colors">Retirer</button>
                    </form>
                @endif
            </div>
        </div>
        @if($candidature->note_motivation)
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-400 mb-1">Note de motivation</p>
                <p class="text-sm text-gray-600 line-clamp-2">{{ $candidature->note_motivation }}</p>
            </div>
        @endif
    </div>
@empty
    <div class="text-center py-16 text-gray-400 bg-white border border-gray-200 rounded-xl">
        <p class="text-lg font-medium text-gray-500">Aucune candidature</p>
        <p class="text-sm mt-2">
            <a href="{{ route('job-offers.index') }}" class="group inline-flex items-center gap-1 text-primary">
                <span class="group-hover:underline">Parcourir les offres disponibles</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </p>
    </div>
@endforelse

<div class="mt-6">{{ $candidatures->links() }}</div>

@endsection