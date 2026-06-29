@extends('layouts.app')

@section('title', 'Créer une démarche')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-primary">← Tableau de bord</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Nouvelle démarche administrative</h1>

        @if($institutions->isEmpty())
            <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-lg p-4 text-sm">
                Vous devez d'abord <a href="{{ route('institutions.create') }}" class="underline font-medium">créer une institution</a> avant d'y rattacher des démarches.
            </div>
        @else
        <form method="POST" action="{{ route('procedures.store') }}" class="space-y-5">
            @csrf

            {{-- Institution --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Institution <span class="text-red-500">*</span></label>
                <select name="institution_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('institution_id') border-red-400 @enderror">
                    <option value="">— Sélectionner votre institution —</option>
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ old('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->nom }}</option>
                    @endforeach
                </select>
                @error('institution_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Titre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de la démarche <span class="text-red-500">*</span></label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    placeholder="Ex : Demande de permis de construire"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('titre') border-red-400 @enderror">
                @error('titre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Coût & Délai --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coût (F CFA)</label>
                    <input type="number" name="cout" value="{{ old('cout') }}" min="0" placeholder="0 = gratuit"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Délai de traitement</label>
                    <input type="text" name="delai" value="{{ old('delai') }}" placeholder="Ex : 15 jours ouvrables"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            {{-- Lieu de dépôt --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de dépôt du dossier</label>
                <input type="text" name="lieu_depot" value="{{ old('lieu_depot') }}"
                    placeholder="Ex : Direction des Ressources Humaines, Lomé"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            {{-- Lien en ligne --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lien en ligne (si disponible)</label>
                <input type="url" name="lien_en_ligne" value="{{ old('lien_en_ligne') }}" placeholder="https://..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            {{-- ─── Pièces & documents requis ──────────────────────────── --}}
            <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-800">Pièces & documents requis</h2>
                    <span class="text-xs text-gray-400">Ajoutez chaque document séparément</span>
                </div>

                {{-- Liste dynamique des pièces --}}
                <div id="pieces-list" class="space-y-2">
                    {{-- Les pièces ajoutées apparaissent ici --}}
                    @if(old('pieces'))
                        @foreach(old('pieces') as $i => $piece)
                        <div class="piece-row flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                            <input type="text" name="pieces[{{ $i }}][libelle]"
                                value="{{ $piece['libelle'] }}"
                                placeholder="Ex : Carte Nationale d'Identité"
                                class="flex-1 bg-transparent text-sm outline-none text-gray-800 placeholder-gray-400">
                            <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap cursor-pointer">
                                <input type="checkbox" name="pieces[{{ $i }}][est_obligatoire]" value="1"
                                    {{ isset($piece['est_obligatoire']) ? 'checked' : '' }}
                                    class="accent-primary">
                                Obligatoire
                            </label>
                            <button type="button" onclick="this.closest('.piece-row').remove()"
                                class="text-gray-300 hover:text-red-400 transition-colors text-lg leading-none">×</button>
                        </div>
                        @endforeach
                    @endif
                </div>

                {{-- Bouton ajouter --}}
                <button type="button" id="btn-add-piece"
                    class="flex items-center gap-2 text-sm text-primary hover:text-primary-dark font-medium transition-colors">
                    <span class="text-lg leading-none">+</span> Ajouter une pièce
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Créer la démarche
                </button>
                <a href="{{ route('dashboard') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    let pieceIndex = {{ old('pieces') ? count(old('pieces')) : 0 }};

    document.getElementById('btn-add-piece').addEventListener('click', function () {
        const list = document.getElementById('pieces-list');

        const row = document.createElement('div');
        row.className = 'piece-row flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 animate-fade-up';
        row.innerHTML = `
            <input type="text" name="pieces[${pieceIndex}][libelle]"
                placeholder="Ex : Carte Nationale d'Identité"
                class="flex-1 bg-transparent text-sm outline-none text-gray-800 placeholder-gray-400"
                autofocus>
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