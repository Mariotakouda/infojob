@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>
        <p class="text-sm text-gray-500 mt-1">Gérez vos informations personnelles et vos identifiants de connexion.</p>
    </div>

    {{-- ── Section 1 : Informations générales ─────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm mb-6">
        <h2 class="text-base font-semibold text-gray-800 mb-5">Informations générales</h2>

        @if (session('success_info'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                {{ session('success_info') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-info') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('name') border-red-400 @enderror"
                >
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input
                    type="text"
                    id="telephone"
                    name="telephone"
                    value="{{ old('telephone', $user->telephone) }}"
                    placeholder="+228 90 00 00 00"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('telephone') border-red-400 @enderror"
                >
                @error('telephone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-1">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    {{-- ── Section 2 : Changer l'email ────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm mb-6">
        <h2 class="text-base font-semibold text-gray-800 mb-1">Adresse email</h2>
        <p class="text-xs text-gray-400 mb-5">Email actuel : <span class="font-medium text-gray-600">{{ $user->email }}</span></p>

        @if (session('success_email'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                {{ session('success_email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-email') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Nouvel email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('email') border-red-400 @enderror"
                    placeholder="nouvel@email.com"
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirmez avec votre mot de passe</label>
                <input
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password_confirm') border-red-400 @enderror"
                    placeholder="••••••••"
                >
                @error('password_confirm')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-1">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Changer l'email
                </button>
            </div>
        </form>
    </div>

    {{-- ── Section 3 : Changer le mot de passe ────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800 mb-1">Mot de passe</h2>
        <p class="text-xs text-gray-400 mb-5">Minimum 8 caractères, avec majuscules et chiffres.</p>

        @if (session('success_password'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                {{ session('success_password') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Ancien mot de passe</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('current_password') border-red-400 @enderror"
                    placeholder="••••••••"
                >
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password') border-red-400 @enderror"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="••••••••"
                >
            </div>

            <div class="pt-1">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Changer le mot de passe
                </button>
            </div>
        </form>
    </div>

</div>
@endsection