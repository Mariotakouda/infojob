@extends('layouts.app')

@section('title', 'Modifier — ' . $jobOffer->titre)

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('job-offers.show', $jobOffer) }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            {{ $jobOffer->titre }}
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Modifier l'offre</h1>

        <form method="POST" action="{{ route('job-offers.update', $jobOffer) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                <p class="text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    {{ $jobOffer->institution->nom }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de l'offre <span class="text-red-500">*</span></label>
                <input type="text" name="titre" value="{{ old('titre', $jobOffer->titre) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('titre') border-red-400 @enderror">
                @error('titre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-400 @enderror">{{ old('description', $jobOffer->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de contrat <span class="text-red-500">*</span></label>
                    <select name="type_contrat" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('type_contrat') border-red-400 @enderror">
                        @foreach(['CDI' => 'CDI', 'CDD' => 'CDD', 'Stage' => 'Stage', 'Prestation_Artisanale' => 'Prestation artisanale'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type_contrat', $jobOffer->type_contrat) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type_contrat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Métier / Profession <span class="text-red-500">*</span></label>
                    <select name="metier" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('metier') border-red-400 @enderror">
                        <option value="">— Sélectionner —</option>
                        @foreach($metiers as $metier)
                            <option value="{{ $metier }}" {{ old('metier', $jobOffer->metier) === $metier ? 'selected' : '' }}>{{ $metier }}</option>
                        @endforeach
                    </select>
                    @error('metier')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lieu <span class="text-red-500">*</span></label>
                <input type="text" name="lieu" value="{{ old('lieu', $jobOffer->lieu) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('lieu') border-red-400 @enderror">
                @error('lieu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget / Salaire (F CFA)</label>
                    <input type="number" name="budget_salaire" value="{{ old('budget_salaire', $jobOffer->budget_salaire) }}" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('budget_salaire') border-red-400 @enderror">
                    @error('budget_salaire')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration <span class="text-red-500">*</span></label>
                    <input type="date" name="date_expiration" value="{{ old('date_expiration', $jobOffer->date_expiration->format('Y-m-d')) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('date_expiration') border-red-400 @enderror">
                    @error('date_expiration')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Enregistrer
                </button>
                <a href="{{ route('job-offers.show', $jobOffer) }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <form method="POST" action="{{ route('job-offers.destroy', $jobOffer) }}"
                    class="ml-auto" onsubmit="return confirm('Supprimer définitivement cette offre ?')">
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
