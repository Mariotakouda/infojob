@extends('layouts.app')

@section('title', 'Modifier — ' . $procedure->titre)

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('procedures.show', $procedure) }}" class="text-sm text-gray-400 hover:text-primary">← {{ $procedure->titre }}</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Modifier la démarche</h1>

        <form method="POST" action="{{ route('procedures.update', $procedure) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                <p class="text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    {{ $procedure->institution->nom }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="titre" value="{{ old('titre', $procedure->titre) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">{{ old('description', $procedure->description) }}</textarea>
            </div>

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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu de dépôt</label>
                <input type="text" name="lieu_depot" value="{{ old('lieu_depot', $procedure->lieu_depot) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lien en ligne</label>
                <input type="url" name="lien_en_ligne" value="{{ old('lien_en_ligne', $procedure->lien_en_ligne) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

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