@extends('layouts.app')

@section('title', 'Créer une institution')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-primary">← Tableau de bord</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Ajouter une institution</h1>

        <form method="POST" action="{{ route('institutions.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'institution <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('nom') border-red-400 @enderror">
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('type') border-red-400 @enderror">
                    <option value="">— Sélectionner —</option>
                    @foreach(['ministere' => 'Ministère','mairie' => 'Mairie','prefecture' => 'Préfecture','direction' => 'Direction','presidence' => 'Présidence','entreprise_privee' => 'Entreprise privée','particulier' => 'Particulier'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-500">*</span></label>
                    <input type="text" name="ville" value="{{ old('ville') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('ville') border-red-400 @enderror">
                    @error('ville')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact public</label>
                    <input type="text" name="contact_public" value="{{ old('contact_public') }}" placeholder="email ou téléphone"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse complète <span class="text-red-500">*</span></label>
                <input type="text" name="adresse" value="{{ old('adresse') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('adresse') border-red-400 @enderror">
                @error('adresse')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Créer l'institution
                </button>
                <a href="{{ route('dashboard') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection