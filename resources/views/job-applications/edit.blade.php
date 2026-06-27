@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-primary">← Tableau de bord</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Modifier mon profil</h1>
        <p class="text-sm text-amber-600 mb-6">⚠ Toute modification repassera en modération.</p>

        <form method="POST" action="{{ route('job-applications.update', $jobApplication) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre du profil <span class="text-red-500">*</span></label>
                <input type="text" name="titre_profil" value="{{ old('titre_profil', $jobApplication->titre_profil) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Secteur d'activité <span class="text-red-500">*</span></label>
                <input type="text" name="secteur_activite" value="{{ old('secteur_activite', $jobApplication->secteur_activite) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Compétences <span class="text-red-500">*</span></label>
                <textarea name="competences" rows="3" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">{{ old('competences', $jobApplication->competences) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Séparez par des virgules.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-500">*</span></label>
                <input type="text" name="ville" value="{{ old('ville', $jobApplication->ville) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="disponibilite" value="1"
                        {{ old('disponibilite', $jobApplication->disponibilite) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary focus:ring-primary w-4 h-4">
                    <span class="text-sm font-medium text-gray-700">Disponible pour des missions / emplois</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Enregistrer
                </button>
                <a href="{{ route('dashboard') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <form method="POST" action="{{ route('job-applications.destroy', $jobApplication) }}"
                    class="ml-auto" onsubmit="return confirm('Supprimer définitivement ce profil ?')">
                    @csrf @method('DELETE')
                    <button class="border border-red-200 text-red-600 px-3 py-1.5 rounded-lg text-sm hover:bg-red-50 transition-colors">
                        Supprimer
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>

@endsection