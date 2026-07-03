@extends('layouts.app')

@section('title', 'Booster — ' . $jobOffer->titre)

@section('content')

<div class="mb-6">
    <a href="{{ route('job-offers.show', $jobOffer) }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        {{ $jobOffer->titre }}
    </a>
</div>

<div class="max-w-lg mx-auto animate-fade-up">
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900">Mettre en avant cette offre</h1>
        <p class="text-sm text-gray-500 mt-1">
            Votre offre apparaîtra en tête de liste sur « Offres d'emploi & chantiers » pendant 7 jours.
        </p>

        @if ($jobOffer->estActivementBoostee())
            <div class="mt-4 bg-blue-50 border border-blue-100 text-blue-700 text-sm rounded-lg px-4 py-3">
                Cette offre est déjà boostée jusqu'au {{ $jobOffer->boost_expire_at->format('d/m/Y à H:i') }}.
                En payant maintenant, la mise en avant sera prolongée de 7 jours supplémentaires.
            </div>
        @endif

        <div class="mt-5 flex items-baseline gap-1">
            <span class="text-3xl font-bold text-gray-900">{{ number_format($prix, 0, ',', ' ') }}</span>
            <span class="text-gray-500">F CFA</span>
        </div>

        @if ($errors->any())
            <div class="mt-4 bg-red-50 border border-red-100 text-red-700 text-sm rounded-lg px-4 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('paiements.boost-initier', $jobOffer) }}" class="mt-5 space-y-4">
            @csrf

            <div>
                <label for="reseau" class="block text-sm font-medium text-gray-700 mb-1.5">Moyen de paiement</label>
                <select name="reseau" id="reseau" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="FLOOZ" {{ old('reseau') === 'FLOOZ' ? 'selected' : '' }}>Flooz (Moov)</option>
                    <option value="TMONEY" {{ old('reseau') === 'TMONEY' ? 'selected' : '' }}>T-Money (Togocom)</option>
                </select>
            </div>

            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1.5">Numéro de téléphone</label>
                <input type="text" name="telephone" id="telephone" required
                    value="{{ old('telephone') }}"
                    placeholder="90 12 34 56"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                <p class="text-xs text-gray-400 mt-1">Vous recevrez une demande de confirmation sur ce numéro.</p>
            </div>

            <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 bg-primary text-white px-4 py-3 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                Payer {{ number_format($prix, 0, ',', ' ') }} F CFA
            </button>

            <p class="text-xs text-center text-gray-400">
                Vous allez être redirigé vers la page de paiement sécurisée PayGate.
            </p>
        </form>
    </div>
</div>

@endsection
