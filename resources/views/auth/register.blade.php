@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-8">
    <div class="w-full max-w-md animate-fade-up">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Créer mon compte</h1>
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
                        placeholder="Gnimdou esso"
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
                        placeholder="gnimdou@gmail.com"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                        Téléphone <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <div class="flex rounded-lg border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-primary @error('telephone') border-red-400 @enderror">
                        <span class="flex items-center gap-1.5 bg-gray-50 border-r border-gray-300 px-3 text-sm text-gray-600 select-none">
                            <span class="text-base leading-none">🇹🇬</span> +228
                        </span>
                        <input
                            type="tel"
                            id="telephone"
                            name="telephone"
                            inputmode="numeric"
                            autocomplete="tel-national"
                            value="{{ old('telephone') }}"
                            maxlength="8"
                            pattern="[0-9]{8}"
                            title="8 chiffres, sans le +228"
                            class="w-full border-0 px-3 py-2.5 text-sm focus:outline-none focus:ring-0"
                            placeholder="90 00 00 00"
                        >
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Saisissez uniquement vos 8 chiffres, l'indicatif +228 est déjà pris en compte.</p>
                    @error('telephone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Je suis</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="min-w-0 flex items-center gap-2 border border-gray-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-green-50 transition-colors overflow-hidden">
                            <input type="radio" name="role" value="citoyen" {{ old('role', 'citoyen') === 'citoyen' ? 'checked' : '' }} class="text-primary shrink-0">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-800 break-words leading-tight">Citoyen/Artisan</p>
                                <p class="text-[11px] text-gray-500 break-words leading-tight">Je cherche du travail</p>
                            </div>
                        </label>
                        <label class="min-w-0 flex items-center gap-2 border border-gray-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-green-50 transition-colors overflow-hidden">
                            <input type="radio" name="role" value="recruteur" {{ old('role') === 'recruteur' ? 'checked' : '' }} class="text-primary shrink-0">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-800 break-words leading-tight">Recruteur/Institution</p>
                                <p class="text-[11px] text-gray-500 break-words leading-tight">Je publie des offres</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mot de passe avec toggle --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('password') border-red-400 @enderror"
                            placeholder="Minimum 8 caractères"
                        >
                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4 eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="w-4 h-4 eye-off hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmation mot de passe avec toggle --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Répétez votre mot de passe"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation', this)"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4 eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="w-4 h-4 eye-off hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
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

<script>
function togglePassword(fieldId, btn) {
    const input  = document.getElementById(fieldId);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeOff  = btn.querySelector('.eye-off');

    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeOff.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeOff.classList.add('hidden');
    }
}
</script>
@endsection
