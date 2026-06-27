<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/iconlogo.png') }}">
    <title>@yield('title', 'TravailTogo') — Plateforme emploi et démarches au Togo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1a6b3c', light: '#2d9e5f', dark: '#124d2b' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="infojob" class="h-20">
                </a>

                {{-- Liens principaux --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                    <a href="{{ route('job-offers.index') }}" class="hover:text-primary transition-colors">Offres d'emploi</a>
                    <a href="{{ route('procedures.index') }}" class="hover:text-primary transition-colors">Démarches</a>
                    <a href="{{ route('job-applications.index') }}" class="hover:text-primary transition-colors">Artisans</a>
                    <a href="{{ route('institutions.index') }}" class="hover:text-primary transition-colors">Institutions</a>
                </div>

                {{-- Auth --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-primary font-medium">
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-primary font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="text-sm bg-primary text-white hover:bg-primary-dark px-4 py-2 rounded-lg transition-colors">
                            S'inscrire
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Contenu principal --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 mt-16 py-8 text-center text-sm text-gray-500">
        © {{ date('Y') }} TravailTogo — Plateforme emploi, démarches & artisanat au Togo
    </footer>

</body>
</html>
