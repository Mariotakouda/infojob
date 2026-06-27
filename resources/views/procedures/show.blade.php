@extends('layouts.app')

@section('title', $procedure->titre)

@section('content')

<div class="mb-6">
    <a href="{{ route('procedures.index') }}" class="text-sm text-gray-400 hover:text-primary">← Toutes les démarches</a>
</div>

<div class="max-w-3xl">

    {{-- En-tête --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <p class="text-xs text-gray-400 mb-1">
                    <a href="{{ route('institutions.show', $procedure->institution) }}" class="hover:text-primary">
                        {{ $procedure->institution->nom }}
                    </a>
                    · {{ $procedure->institution->ville }}
                </p>
                <h1 class="text-2xl font-bold text-gray-900">{{ $procedure->titre }}</h1>
            </div>
            @auth
                @if(auth()->user()->institutions()->pluck('id')->contains($procedure->institution_id) || auth()->user()->isAdmin())
                    <div class="flex gap-2">
                        <a href="{{ route('procedures.edit', $procedure) }}"
                            class="border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('procedures.destroy', $procedure) }}"
                            onsubmit="return confirm('Supprimer cette démarche ?')">
                            @csrf @method('DELETE')
                            <button class="border border-red-200 text-red-600 px-3 py-1.5 rounded-lg text-sm hover:bg-red-50 transition-colors">
                                Supprimer
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        <div class="flex flex-wrap gap-4 mt-5 pt-5 border-t border-gray-100">
            @if($procedure->cout)
                <div class="flex flex-col">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Coût</span>
                    <span class="font-semibold text-primary mt-0.5">{{ $procedure->coutFormate() }}</span>
                </div>
            @else
                <div class="flex flex-col">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Coût</span>
                    <span class="font-semibold text-green-600 mt-0.5">Gratuit</span>
                </div>
            @endif
            @if($procedure->delai)
                <div class="flex flex-col">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Délai</span>
                    <span class="font-medium text-gray-900 mt-0.5">{{ $procedure->delai }}</span>
                </div>
            @endif
            @if($procedure->lieu_depot)
                <div class="flex flex-col">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Lieu de dépôt</span>
                    <span class="font-medium text-gray-900 mt-0.5">{{ $procedure->lieu_depot }}</span>
                </div>
            @endif
        </div>

        @if($procedure->lien_en_ligne)
            <div class="mt-4">
                <a href="{{ $procedure->lien_en_ligne }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-1 text-sm text-primary hover:underline font-medium">
                    🔗 Effectuer cette démarche en ligne →
                </a>
            </div>
        @endif
    </div>

    {{-- Description --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-3">Description</h2>
        <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $procedure->description }}</div>
    </div>

    {{-- Pièces requises --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-4">Pièces & documents requis</h2>

        @forelse($procedure->requirements->sortByDesc('est_obligatoire') as $req)
            <div class="flex items-start gap-3 py-3 border-t border-gray-100 first:border-0">
                <span class="mt-0.5 flex-shrink-0">
                    @if($req->est_obligatoire)
                        <span class="inline-block w-5 h-5 bg-red-100 text-red-600 rounded-full text-xs flex items-center justify-center font-bold" title="Obligatoire">!</span>
                    @else
                        <span class="inline-block w-5 h-5 bg-gray-100 text-gray-400 rounded-full text-xs flex items-center justify-center" title="Optionnel">○</span>
                    @endif
                </span>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">
                        {{ $req->libelle }}
                        @if($req->est_obligatoire)
                            <span class="text-red-500 text-xs ml-1">*</span>
                        @endif
                    </p>
                    @if($req->description)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $req->description }}</p>
                    @endif
                </div>

                {{-- Suppression par le recruteur propriétaire --}}
                @auth
                    @if(auth()->user()->institutions()->pluck('id')->contains($procedure->institution_id) || auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('procedures.requirements.destroy', [$procedure, $req]) }}">
                            @csrf @method('DELETE')
                            <button class="text-xs text-gray-300 hover:text-red-400 transition-colors ml-2">✕</button>
                        </form>
                    @endif
                @endauth
            </div>
        @empty
            <p class="text-sm text-gray-400 py-4 text-center">Aucune pièce définie pour l'instant.</p>
        @endforelse

        <p class="text-xs text-gray-400 mt-4"><span class="text-red-500">*</span> Obligatoire</p>

        {{-- Formulaire d'ajout de pièce (recruteur) --}}
        @auth
            @if(auth()->user()->institutions()->pluck('id')->contains($procedure->institution_id))
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Ajouter une pièce</h3>
                    <form method="POST" action="{{ route('procedures.requirements.store', $procedure) }}" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" name="libelle" placeholder="Libellé de la pièce *" required
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                            <input type="text" name="description" placeholder="Précision (optionnel)"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="est_obligatoire" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                                Pièce obligatoire
                            </label>
                            <button type="submit" class="bg-primary text-white px-4 py-1.5 rounded-lg text-sm hover:bg-primary-dark transition-colors">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endauth
    </div>

</div>

@endsection