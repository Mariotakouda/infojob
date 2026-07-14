@extends('layouts.app')

@section('title', 'Publier une offre')

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
        <h1 class="text-xl font-bold text-gray-900 mb-1">Publier une offre</h1>
        <p class="text-sm text-gray-500 mb-6">L'offre sera visible publiquement après modération.</p>

        @if($institutions->isEmpty())
            <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-lg p-4 text-sm">
                Vous devez avoir une <a href="{{ route('institutions.create') }}" class="underline font-medium">institution vérifiée</a> pour publier une offre.
                Si vous venez d'en créer une, patientez : elle doit d'abord être validée par un administrateur (badge « Vérifiée »).
                <a href="{{ route('dashboard') }}" class="underline font-medium">Voir le statut de mes institutions</a>
            </div>
        @else
        <form method="POST" action="{{ route('job-offers.store') }}" enctype="multipart/form-data" class="space-y-5">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de l'offre <span class="text-red-500">*</span></label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    placeholder="Ex : Maçon qualifié pour chantier résidentiel"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('titre') border-red-400 @enderror">
                @error('titre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required
                    placeholder="Décrivez le poste, les missions ou les travaux à réaliser..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Affiche de l'offre <span class="text-gray-400 font-normal">(optionnel)</span>
                </label>
                <input type="file" name="affiche" accept="image/png,image/jpeg,image/webp"
                    class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gray-100 file:text-gray-700 file:text-xs hover:file:bg-gray-200 @error('affiche') border-red-400 @enderror">
                <p class="text-xs text-gray-400 mt-1">Une image (flyer, visuel...) affichée en haut de l'annonce. JPG, PNG ou WEBP, 4 Mo max.</p>
                @error('affiche')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de contrat <span class="text-red-500">*</span></label>
                    <select name="type_contrat" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('type_contrat') border-red-400 @enderror">
                        <option value="">— Sélectionner —</option>
                        <option value="CDI" {{ old('type_contrat') === 'CDI' ? 'selected' : '' }}>CDI</option>
                        <option value="CDD" {{ old('type_contrat') === 'CDD' ? 'selected' : '' }}>CDD</option>
                        <option value="Stage" {{ old('type_contrat') === 'Stage' ? 'selected' : '' }}>Stage</option>
                        <option value="Prestation_Artisanale" {{ old('type_contrat') === 'Prestation_Artisanale' ? 'selected' : '' }}>Prestation artisanale</option>
                    </select>
                    @error('type_contrat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Métier / Profession <span class="text-red-500">*</span></label>
                    <select name="metier" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('metier') border-red-400 @enderror">
                        <option value="">— Sélectionner —</option>
                        @foreach($metiers as $metier)
                            <option value="{{ $metier }}" {{ old('metier') === $metier ? 'selected' : '' }}>{{ $metier }}</option>
                        @endforeach
                    </select>
                    @error('metier')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu <span class="text-red-500">*</span></label>
                <input type="text" name="lieu" value="{{ old('lieu') }}" required
                    placeholder="Ex : Lomé"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('lieu') border-red-400 @enderror">
                @error('lieu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget / Salaire (F CFA)</label>
                    <input type="number" name="budget_salaire" value="{{ old('budget_salaire') }}" min="0" placeholder="Laisser vide si non précisé"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('budget_salaire') border-red-400 @enderror">
                    @error('budget_salaire')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration <span class="text-red-500">*</span></label>
                    <input type="date" name="date_expiration" value="{{ old('date_expiration') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('date_expiration') border-red-400 @enderror">
                    @error('date_expiration')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Publier l'offre
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
