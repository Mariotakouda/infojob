<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PayGate\LaravelPayGateGlobal\Facades\PayGateGlobal;

class PaymentController extends Controller
{
    /**
     * Affiche la page de confirmation avant paiement pour booster une offre.
     */
    public function boostForm(JobOffer $jobOffer)
    {
        $this->autoriserOffre($jobOffer);

        return view('job-offers.boost', [
            'jobOffer' => $jobOffer,
            'prix'     => Payment::PRIX_BOOST_OFFRE,
        ]);
    }

    /**
     * Initie le paiement PayGate et redirige le recruteur vers la page
     * de paiement hébergée (FLOOZ / T-Money).
     */
    public function boostInitier(Request $request, JobOffer $jobOffer)
    {
        $this->autoriserOffre($jobOffer);

        $validated = $request->validate([
            'telephone' => ['required', 'string', 'regex:/^(\+228)?[0-9]{8}$/'],
            'reseau'    => ['required', 'in:FLOOZ,TMONEY'],
        ]);

        $identifier = 'BOOST_' . $jobOffer->id . '_' . now()->timestamp . '_' . Str::random(6);

        // On enregistre le paiement en attente AVANT de rediriger l'utilisateur,
        // pour pouvoir le retrouver quand PayGate confirmera (webhook).
        $payment = Payment::create([
            'user_id'        => $request->user()->id,
            'payable_type'   => JobOffer::class,
            'payable_id'     => $jobOffer->id,
            'identifier'     => $identifier,
            'montant'        => Payment::PRIX_BOOST_OFFRE,
            'moyen_paiement' => $validated['reseau'],
            'telephone'      => $validated['telephone'],
            'statut'         => 'en_attente',
            'motif'          => 'Mise en avant offre #' . $jobOffer->id . ' — ' . $jobOffer->titre,
        ]);

        $paymentUrl = PayGateGlobal::generatePaymentUrl([
            'amount'      => $payment->montant,
            'identifier'  => $identifier,
            'description' => Str::limit($payment->motif, 100),
            'success_url' => route('paiements.retour', $jobOffer),
            'phone'       => $validated['telephone'],
            'network'     => $validated['reseau'],
        ]);

        return redirect($paymentUrl);
    }

    /**
     * Page sur laquelle PayGate redirige le client APRÈS le paiement.
     * Ceci est un simple affichage — la source de vérité reste le webhook,
     * qui peut arriver avant ou après ce retour.
     */
    public function retour(JobOffer $jobOffer)
    {
        $jobOffer->refresh();

        $dernierPaiement = $jobOffer->payments()->latest()->first();

        return view('job-offers.boost-retour', [
            'jobOffer' => $jobOffer,
            'payment'  => $dernierPaiement,
        ]);
    }

    /**
     * S'assure que l'utilisateur connecté possède bien cette offre
     * (via son institution) avant de le laisser la booster.
     */
    private function autoriserOffre(JobOffer $jobOffer): void
    {
        abort_unless(
            $jobOffer->institution->user_id === request()->user()->id
                || request()->user()->isAdmin(),
            403,
            "Vous n'êtes pas autorisé à booster cette offre."
        );
    }
}
