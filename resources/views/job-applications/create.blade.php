@extends('layouts.app')

@section('title', 'Créer mon profil artisan')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-primary">← Tableau de bord</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Créer mon profil / vitrine</h1>
        <p class="text-sm text-gray-500 mb-6">Votre profil sera visible après validation par un modérateur.</p>

        <form method="POST" action="{{ route('job-applications.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre du profil <span class="text-red-500">*</span></label>
                <input type="text" name="titre_profil" value="{{ old('titre_profil') }}" required
                    placeholder="Ex : Maçon qualifié, Développeur Web Full-Stack, Coiffeuse..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('titre_profil') border-red-400 @enderror">
                @error('titre_profil')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Secteur d'activité <span class="text-red-500">*</span></label>
                <input type="text" name="secteur_activite" value="{{ old('secteur_activite') }}" required
                    placeholder="Ex : BTP & Artisanat, Informatique & Numérique, Commerce..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('secteur_activite') border-red-400 @enderror">
                @error('secteur_activite')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Compétences <span class="text-red-500">*</span></label>
                <textarea name="competences" rows="3" required
                    placeholder="Séparez vos compétences par des virgules : PHP, Laravel, MySQL, Git..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('competences') border-red-400 @enderror">{{ old('competences') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Séparez par des virgules pour créer des tags.</p>
                @error('competences')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-500">*</span></label>
                <input type="text" name="ville" value="{{ old('ville') }}" required
                    placeholder="Ex : Lomé, Kara, Sokodé..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('ville') border-red-400 @enderror">
                @error('ville')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="disponibilite" value="1" {{ old('disponibilite') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary focus:ring-primary w-4 h-4">
                    <span class="text-sm font-medium text-gray-700">Je suis actuellement disponible pour des missions / emplois</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Soumettre mon profil
                </button>
                <a href="{{ route('dashboard') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection