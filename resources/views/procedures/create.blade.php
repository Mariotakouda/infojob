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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de la démarche <span class="text-red-500">*</span></label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    placeholder="Ex : Demande de permis de construire"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('titre') border-red-400 @enderror">
                @error('titre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de dépôt du dossier</label>
                <input type="text" name="lieu_depot" value="{{ old('lieu_depot') }}"
                    placeholder="Ex : Direction des Ressources Humaines, Lomé"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lien en ligne (si disponible)</label>
                <input type="url" name="lien_en_ligne" value="{{ old('lien_en_ligne') }}" placeholder="https://..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

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