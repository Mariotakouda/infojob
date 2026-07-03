<?php

namespace App\Listeners;

use App\Models\JobOffer;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use PayGate\LaravelPayGateGlobal\Events\PaymentReceived;
use PayGate\LaravelPayGateGlobal\Facades\PayGateGlobal;

class ActivateJobOfferBoostListener
{
    /**
     * PayGate a appelé notre webhook. IMPORTANT : cet événement ne transporte
     * AUCUN statut (voir vendor/filano/laravel-paygate-global/src/Events/PaymentReceived.php
     * — seulement txReference/identifier/amount/datetime/paymentMethod/phoneNumber)
     * et se déclenche pour tout appel webhook, pas seulement les succès. On ne
     * peut donc pas se fier à sa simple réception comme preuve de paiement :
     * on revérifie systématiquement le statut réel auprès de l'API PayGate.
     */
    public function handle(PaymentReceived $event): void
    {
        $payment = Payment::where('identifier', $event->identifier)->first();

        if (! $payment) {
            Log::warning('Webhook PayGate reçu pour un paiement inconnu', [
                'identifier' => $event->identifier,
            ]);
            return;
        }

        // Évite de retraiter un paiement déjà confirmé (le webhook peut être
        // renvoyé plusieurs fois par PayGate).
        if ($payment->estReussi()) {
            return;
        }

        try {
            $statut = PayGateGlobal::checkPaymentStatusByIdentifier($event->identifier);
        } catch (\Throwable $e) {
            Log::error('Échec de la vérification du statut PayGate', [
                'identifier' => $event->identifier,
                'erreur'     => $e->getMessage(),
            ]);
            return;
        }

        $reussi = (int) ($statut['status'] ?? null) === 0;

        $payment->update([
            'tx_reference' => $event->txReference,
            'statut'       => $reussi ? 'reussi' : 'echoue',
            'paye_a'       => $reussi ? now() : null,
        ]);

        if (! $reussi) {
            Log::info('Paiement PayGate non confirmé après revérification', [
                'identifier' => $event->identifier,
                'statut_api' => $statut['status'] ?? null,
            ]);
            return;
        }

        if ($payment->payable_type === JobOffer::class) {
            $jobOffer = $payment->payable;

            if ($jobOffer) {
                $jobOffer->update([
                    'est_boostee'     => true,
                    // Si l'offre était déjà boostée, on prolonge à partir
                    // de la date d'expiration en cours plutôt que d'écraser.
                    'boost_expire_at' => ($jobOffer->boost_expire_at?->isFuture()
                            ? $jobOffer->boost_expire_at
                            : now())->addDays(7),
                ]);
            }
        }
    }
}
