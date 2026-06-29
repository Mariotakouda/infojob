<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureStatutRequest;
use App\Models\Candidature;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    public function index(Request $request): View
    {
        $candidatures = $request->user()
            ->candidatures()
            ->with('jobOffer.institution')
            ->latest()
            ->paginate(10);

        return view('candidatures.index', compact('candidatures'));
    }

    public function store(StoreCandidatureRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        // Vérifie que l'offre est active
        if ($jobOffer->statut !== 'publie' || $jobOffer->estExpiree()) {
            return back()->with('error', 'Cette offre n\'est plus disponible.');
        }

        // Vérifie l'unicité (double sécurité en plus de la contrainte BDD)
        $existe = Candidature::where('user_id', auth()->id())
            ->where('job_offer_id', $jobOffer->id)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $validated = $request->validated();

        Candidature::create([
            'user_id'             => auth()->id(),
            'job_offer_id'        => $jobOffer->id,
            'note_motivation'     => $validated['note_motivation'] ?? null,
            'statut_candidature'  => 'recue',
        ]);

        return back()->with('success', 'Votre candidature a bien été envoyée.');
    }

    public function destroy(Candidature $candidature): RedirectResponse
    {
        // Seul le propriétaire peut retirer sa candidature
        if ($candidature->user_id !== auth()->id()) {
            abort(403);
        }

        if ($candidature->statut_candidature !== 'recue') {
            return back()->with('error', 'Vous ne pouvez plus retirer cette candidature.');
        }

        $candidature->delete();

        return back()->with('success', 'Candidature retirée.');
    }

    // Réservé recruteur — modifier le statut d'une candidature reçue
    public function updateStatut(UpdateCandidatureStatutRequest $request, Candidature $candidature): RedirectResponse
    {
        // Vérifie que le recruteur possède l'offre concernée
        $institution = $candidature->jobOffer->institution;
        if ($institution->user_id !== auth()->id()) {
            abort(403);
        }

        $candidature->update($request->validated());

        return back()->with('success', 'Statut de la candidature mis à jour.');
    }
}
