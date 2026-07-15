@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Tableau de bord
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Modifier mon profil</h1>
        <p class="inline-flex items-center gap-1.5 text-sm text-amber-600 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            Toute modification repassera en modération.
        </p>

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
                <p class="text-xs text-gray-400 mt-1">Listez ce que vous savez faire dans votre métier, séparé par des virgules (ex : "coupe, coloration" pour une coiffeuse, ou "vidange, freins" pour un mécanicien).</p>
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