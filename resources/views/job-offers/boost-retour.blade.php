@extends('layouts.app')

@section('title', 'Paiement — ' . $jobOffer->titre)

@section('content')

<div class="max-w-lg mx-auto animate-fade-up">
    <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">

        @if ($payment && $payment->estReussi())
            <div class="w-14 h-14 mx-auto rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Paiement confirmé</h1>
            <p class="text-sm text-gray-500 mt-1">
                Votre offre « {{ $jobOffer->titre }} » est boostée jusqu'au
                {{ $jobOffer->boost_expire_at?->format('d/m/Y à H:i') }}.
            </p>
        @elseif ($payment && $payment->statut === 'en_attente')
            <div class="w-14 h-14 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Paiement en cours de confirmation</h1>
            <p class="text-sm text-gray-500 mt-1">
                Nous attendons la confirmation de PayGate. Cela peut prendre quelques instants.
                Actualisez cette page dans un moment.
            </p>
        @else
            <div class="w-14 h-14 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Paiement non confirmé</h1>
            <p class="text-sm text-gray-500 mt-1">
                Le paiement n'a pas abouti. Vous pouvez réessayer.
            </p>
        @endif

        <a href="{{ route('job-offers.show', $jobOffer) }}"
            class="inline-flex items-center gap-2 mt-6 bg-gray-900 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition-all duration-200">
            Retour à l'offre
        </a>
    </div>
</div>

@endsection
