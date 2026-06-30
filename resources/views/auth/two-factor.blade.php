@extends('layouts.app')

@section('title', 'Vérification en deux étapes')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-md animate-fade-up">

        <div class="text-center mb-8">
            {{-- Icône bouclier --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 10c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.25-8.25-3.286z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Vérification en deux étapes</h1>
            <p class="text-sm text-gray-500 mt-2">
                Un code à 6 chiffres a été envoyé à<br>
                <span class="font-medium text-gray-700">{{ auth()->user()->email }}</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">

            {{-- Message de succès (renvoi du code) --}}
            @if (session('status'))
                <div class="mb-5 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Code de vérification
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        autocomplete="one-time-code"
                        autofocus
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-3 text-center text-2xl font-mono tracking-widest focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('code') border-red-400 @enderror"
                        placeholder="000000"
                        value="{{ old('code') }}"
                    >
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1.5 text-center">
                        Le code expire dans 5 minutes.
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors text-sm"
                >
                    Valider le code
                </button>
            </form>

            {{-- Renvoi du code --}}
            <div class="mt-5 text-center">
                <p class="text-sm text-gray-500">Vous n'avez pas reçu le code ?</p>
                <form method="POST" action="{{ route('two-factor.resend') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-primary font-medium hover:underline mt-1">
                        Renvoyer un nouveau code
                    </button>
                </form>
            </div>

            {{-- Déconnexion --}}
            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">
                        Ce n'est pas moi - me déconnecter
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection