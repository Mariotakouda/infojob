@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-8 animate-fade-up">
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

        <form method="POST" action="{{ route('profile.update-info') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-4">
                @if($user->photo)
                    <img src="{{ $user->photoUrl() }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-full object-cover border border-gray-200">
                @else
                    <div class="w-14 h-14 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                @endif
                <div class="flex items-center gap-2">
                    <label class="cursor-pointer text-sm text-primary font-medium border border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-50 transition-colors">
                        {{ $user->photo ? 'Changer la photo' : 'Ajouter une photo' }}
                        <input type="file" name="photo" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="this.form.requestSubmit()">
                    </label>
                    @if($user->photo)
                        <button type="submit" form="delete-photo-form" onclick="return confirm('Supprimer votre photo de profil ?')"
                            class="text-sm text-red-500 font-medium border border-red-200 rounded-lg px-3 py-2 hover:bg-red-50 transition-colors">
                            Supprimer
                        </button>
                    @endif
                </div>
            </div>
            @error('photo')
                <p class="text-red-500 text-xs -mt-2">{{ $message }}</p>
            @enderror

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
                <div class="flex rounded-lg border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-primary @error('telephone') border-red-400 @enderror">
                    <span class="flex items-center gap-1.5 bg-gray-50 border-r border-gray-300 px-3 text-sm text-gray-600 select-none">
                        <span class="text-base leading-none">🇹🇬</span> +228
                    </span>
                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        inputmode="numeric"
                        maxlength="8"
                        pattern="[0-9]{8}"
                        title="8 chiffres, sans le +228"
                        value="{{ old('telephone', $user->telephone ? preg_replace('/\D/', '', substr($user->telephone, 4)) : '') }}"
                        placeholder="90 00 00 00"
                        class="w-full border-0 px-3 py-2.5 text-sm focus:outline-none focus:ring-0"
                    >
                </div>
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

        <form id="delete-photo-form" method="POST" action="{{ route('profile.destroy-photo') }}" class="hidden">
            @csrf
            @method('DELETE')
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