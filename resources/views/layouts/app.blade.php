<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
    <title>@yield('title', 'TravailTogo') - Plateforme emploi et démarches administratives au Togo</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1a6b3c', light: '#2d9e5f', dark: '#124d2b' },
                    },
                    animation: {
                        'fade-down': 'fadeDown 0.4s ease both',
                        'fade-up':   'fadeUp 0.5s ease both',
                    },
                    keyframes: {
                        fadeDown: {
                            '0%':   { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeUp: {
                            '0%':   { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    }
                }
            }
        }
    </script>
    <style>
        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Nav scroll shrink */
        .nav-scrolled {
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            background: rgba(255,255,255,0.97) !important;
        }

        /* Hero full bleed (robuste : reste pleine largeur même si le parent a un padding) */
        .hero-fullbleed {
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            width: 100vw;
        }

        /* Card hover lift */
        .card-lift {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(26,107,60,0.12);
        }

        /* Underline nav links */
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0;
            width: 0; height: 2px;
            background: #1a6b3c;
            transition: width 0.25s ease;
            border-radius: 2px;
        }
        .nav-link:hover::after { width: 100%; }

        /* Stagger children */
        .stagger > * { animation: fadeUp 0.5s ease both; }
        .stagger > *:nth-child(1) { animation-delay: 0.05s; }
        .stagger > *:nth-child(2) { animation-delay: 0.12s; }
        .stagger > *:nth-child(3) { animation-delay: 0.19s; }
        .stagger > *:nth-child(4) { animation-delay: 0.26s; }

        /* ─── Animation du Slider CSS d'Arrière-plan ─── */
        @keyframes cssSliderFade {
            0%, 100% { opacity: 0; transform: scale(1.05); }
            5%, 33.33% { opacity: 1; transform: scale(1); }
            38.33% { opacity: 0; transform: scale(1.02); }
        }
        .animate-slider-1 { animation: cssSliderFade 15s infinite 0s ease-in-out; }
        .animate-slider-2 { animation: cssSliderFade 15s infinite 5s ease-in-out; }
        .animate-slider-3 { animation: cssSliderFade 15s infinite 10s ease-in-out; }

        @media (prefers-reduced-motion: reduce) {
            *, .stagger > * { animation: none !important; transition: none !important; }
        }

        /* Menu mobile : transition d'ouverture/fermeture */
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        #mobile-menu.open {
            max-height: 500px;
        }

        /* Icônes burger <-> croix */
        #menu-icon-open, #menu-icon-close {
            transition: opacity 0.15s ease, transform 0.15s ease;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">

    {{-- Navigation --}}
    <nav id="main-nav" class="bg-white/95 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50 transition-all duration-300 animate-fade-down">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <a href="{{ route('home') }}" class="flex-shrink-0 transition-transform hover:scale-105 duration-200">
                    <img src="{{ asset('images/logo1.png') }}" alt="TravailTogo" class="h-10">
                </a>

                {{-- Liens principaux (desktop) --}}
                <div class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
                    <a href="{{ route('home') }}"                    class="nav-link hover:text-primary transition-colors">Accueil</a>
                    <a href="{{ route('job-offers.index') }}"        class="nav-link hover:text-primary transition-colors">Offres d'emploi</a>
                    <a href="{{ route('procedures.index') }}"        class="nav-link hover:text-primary transition-colors">Démarches</a>
                    <a href="{{ route('job-applications.index') }}"  class="nav-link hover:text-primary transition-colors">Artisans</a>
                    <a href="{{ route('institutions.index') }}"      class="nav-link hover:text-primary transition-colors">Institutions</a>
                </div>

                {{-- Auth (desktop) + bouton burger (mobile) --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('search') }}" title="Rechercher"
                        class="hidden sm:inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-500 hover:text-primary hover:border-primary/40 hover:bg-primary/5 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </a>

                    {{-- Bloc auth : toujours visible, même sur mobile --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="hidden lg:inline text-sm text-gray-600 hover:text-primary font-medium transition-colors border border-gray-200 hover:border-primary px-3 py-1.5 rounded-lg transition-all duration-200 hover:bg-primary/5">
                                 {{ auth()->user()->name }}
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               title="Mon profil"
                               class="inline-flex items-center gap-1.5 text-sm text-gray-600 hover:text-primary font-medium border border-gray-200 hover:border-primary px-2.5 sm:px-3 py-1.5 rounded-lg transition-all duration-200 hover:bg-primary/5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span class="hidden sm:inline">Mon profil</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm bg-gray-100 hover:bg-gray-200 px-2.5 sm:px-3 py-1.5 rounded-lg transition-colors duration-200">
                                    Déconnexion
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-primary font-medium transition-colors px-1">Connexion</a>
                            <a href="{{ route('register') }}" class="text-sm bg-primary text-white hover:bg-primary-dark px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 hover:shadow-md hover:shadow-primary/20 whitespace-nowrap">
                                S'inscrire
                            </a>
                        @endauth
                    </div>

                    {{-- Bouton hamburger, visible uniquement en mobile --}}
                    <button
                        id="menu-toggle"
                        type="button"
                        aria-label="Ouvrir le menu"
                        aria-expanded="false"
                        aria-controls="mobile-menu"
                        class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:text-primary hover:border-primary/40 hover:bg-primary/5 transition-all duration-200"
                    >
                        <svg id="menu-icon-open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                        </svg>
                        <svg id="menu-icon-close" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        {{-- Menu mobile déroulant --}}
        <div id="mobile-menu" class="md:hidden border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col gap-1 text-sm font-medium text-gray-600">
                <a href="{{ route('home') }}"                   class="px-3 py-2.5 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors">Accueil</a>
                <a href="{{ route('job-offers.index') }}"       class="px-3 py-2.5 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors">Offres d'emploi</a>
                <a href="{{ route('procedures.index') }}"       class="px-3 py-2.5 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors">Démarches</a>
                <a href="{{ route('job-applications.index') }}" class="px-3 py-2.5 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors">Artisans</a>
                <a href="{{ route('institutions.index') }}"     class="px-3 py-2.5 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors">Institutions</a>
                <a href="{{ route('search') }}"                 class="px-3 py-2.5 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors">Rechercher</a>
                @auth
                    <div class="border-t border-gray-100 my-2"></div>
                    <a href="{{ route('dashboard') }}" class="px-3 py-2.5 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors">
                        {{ auth()->user()->name }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 animate-fade-down">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 animate-fade-down">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 002 0V9a1 1 0 00-2 0zm1-4a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Contenu principal --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-12">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 mt-16 py-10 text-center text-sm text-gray-400">
        <p class="font-medium text-gray-500 mb-1">InfoJob-Togo</p>
        <p>© {{ date('Y') }} - Plateforme emploi, démarches & artisanat au Togo</p>
    </footer>

    <script>
        // Nav shrink on scroll
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('nav-scrolled', window.scrollY > 20);
        }, { passive: true });

        // Menu mobile : ouverture / fermeture
        const menuToggle   = document.getElementById('menu-toggle');
        const mobileMenu   = document.getElementById('mobile-menu');
        const iconOpen     = document.getElementById('menu-icon-open');
        const iconClose    = document.getElementById('menu-icon-close');

        function setMenuState(open) {
            mobileMenu.classList.toggle('open', open);
            iconOpen.classList.toggle('hidden', open);
            iconClose.classList.toggle('hidden', !open);
            menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        menuToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('open');
            setMenuState(!isOpen);
        });

        // Ferme le menu automatiquement si on repasse en desktop (resize)
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                setMenuState(false);
            }
        });

        // Ferme le menu après un clic sur un lien (mobile)
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => setMenuState(false));
        });
    </script>

    @stack('scripts')

</body>
</html>