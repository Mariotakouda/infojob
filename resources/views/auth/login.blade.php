@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Connexion à votre compte</h1>
            <p class="text-sm text-gray-500 mt-1">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Créer un compte</a>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('email') border-red-400 @enderror"
                        placeholder="exemple@gmail.com"
                    >
                    @error('email')
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
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary">
                        Se souvenir de moi
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors text-sm"
                >
                    Se connecter
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
