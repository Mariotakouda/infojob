@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-8">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Créer votre compte</h1>
            <p class="text-sm text-gray-500 mt-1">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Se connecter</a>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-400 @enderror"
                        placeholder="Kofi Mensah"
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('email') border-red-400 @enderror"
                        placeholder="kofi@exemple.com"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                        Téléphone WhatsApp <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        value="{{ old('telephone') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="+228 90 00 00 00"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Je suis</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-green-50 transition-colors">
                            <input type="radio" name="role" value="citoyen" {{ old('role', 'citoyen') === 'citoyen' ? 'checked' : '' }} class="text-primary">
                            <div>
                                <p class="text-sm font-medium text-gray-800">Citoyen / Artisan</p>
                                <p class="text-xs text-gray-500">Je cherche du travail</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-green-50 transition-colors">
                            <input type="radio" name="role" value="recruteur" {{ old('role') === 'recruteur' ? 'checked' : '' }} class="text-primary">
                            <div>
                                <p class="text-sm font-medium text-gray-800">Recruteur / Client</p>
                                <p class="text-xs text-gray-500">Je publie des offres</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password') border-red-400 @enderror"
                        placeholder="Minimum 8 caractères"
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Répétez votre mot de passe"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors text-sm"
                >
                    Créer mon compte
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
