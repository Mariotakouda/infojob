@extends('layouts.app')

@section('title', 'Modifier — ' . $procedure->titre)

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('procedures.show', $procedure) }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            {{ $procedure->titre }}
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Modifier la démarche</h1>

        <form method="POST" action="{{ route('procedures.update', $procedure) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Institution (lecture seule) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                <p class="text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    {{ $procedure->institution->nom }}
                </p>
            </div>

            {{-- Titre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="titre" value="{{ old('titre', $procedure->titre) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">{{ old('description', $procedure->description) }}</textarea>
            </div>

            {{-- Coût & Délai --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coût (F CFA)</label>
                    <input type="number" name="cout" value="{{ old('cout', $procedure->cout) }}" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Délai</label>
                    <input type="text" name="delai" value="{{ old('delai', $procedure->delai) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            {{-- Lieu de dépôt --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de dépôt</label>
                <input type="text" name="lieu_depot" value="{{ old('lieu_depot', $procedure->lieu_depot) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            {{-- Lien en ligne --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lien en ligne</label>
                <input type="url" name="lien_en_ligne" value="{{ old('lien_en_ligne', $procedure->lien_en_ligne) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            {{-- ─── Pièces & documents requis ──────────────────────────── --}}
            <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-800">Pièces & documents requis</h2>
                    <span class="text-xs text-gray-400">Modifiez ou supprimez les pièces existantes</span>
                </div>

                <div id="pieces-list" class="space-y-2">
                    {{-- Pièces existantes en base --}}
                    @php $existingPieces = old('pieces') ? collect(old('pieces'))->values() : $procedure->requirements; @endphp
                    @foreach($existingPieces as $i => $piece)
                    <div class="piece-row flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                        {{-- ID caché pour les pièces existantes (mise à jour vs création) --}}
                        @if(is_object($piece) && $piece->id)
                            <input type="hidden" name="pieces[{{ $i }}][id]" value="{{ $piece->id }}">
                        @endif
                        <input type="text"
                            name="pieces[{{ $i }}][libelle]"
                            value="{{ is_object($piece) ? $piece->libelle : ($piece['libelle'] ?? '') }}"
                            placeholder="Ex : Carte Nationale d'Identité"
                            class="flex-1 bg-transparent text-sm outline-none text-gray-800 placeholder-gray-400">
                        <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap cursor-pointer">
                            <input type="checkbox"
                                name="pieces[{{ $i }}][est_obligatoire]"
                                value="1"
                                {{ (is_object($piece) ? $piece->est_obligatoire : isset($piece['est_obligatoire'])) ? 'checked' : '' }}
                                class="accent-primary">
                            Obligatoire
                        </label>
                        <button type="button" onclick="this.closest('.piece-row').remove()"
                            class="text-gray-300 hover:text-red-400 transition-colors text-lg leading-none">×</button>
                    </div>
                    @endforeach
                </div>

                <button type="button" id="btn-add-piece"
                    class="flex items-center gap-2 text-sm text-primary hover:text-primary-dark font-medium transition-colors">
                    <span class="text-lg leading-none">+</span> Ajouter une pièce
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Enregistrer
                </button>
                <a href="{{ route('procedures.show', $procedure) }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let pieceIndex = {{ $procedure->requirements->count() }};

    document.getElementById('btn-add-piece').addEventListener('click', function () {
        const list = document.getElementById('pieces-list');

        const row = document.createElement('div');
        row.className = 'piece-row flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2';
        row.innerHTML = `
            <input type="text" name="pieces[${pieceIndex}][libelle]"
                placeholder="Ex : Attestation d'assurance"
                class="flex-1 bg-transparent text-sm outline-none text-gray-800 placeholder-gray-400">
            <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap cursor-pointer">
                <input type="checkbox" name="pieces[${pieceIndex}][est_obligatoire]" value="1" checked
                    class="accent-primary">
                Obligatoire
            </label>
            <button type="button" onclick="this.closest('.piece-row').remove()"
                class="text-gray-300 hover:text-red-400 transition-colors text-lg leading-none">×</button>
        `;

        list.appendChild(row);
        row.querySelector('input[type="text"]').focus();
        pieceIndex++;
    });
</script>
@endpush