<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureStatutRequest;
use App\Models\Candidature;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $data = [
            'user_id'             => auth()->id(),
            'job_offer_id'        => $jobOffer->id,
            'note_motivation'     => $validated['note_motivation'] ?? null,
            'statut_candidature'  => 'recue',
        ];

        // Le CV et la lettre de motivation sont optionnels (nullable).
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $data['cv_path']         = $file->store('candidatures/cv', 'local');
            $data['cv_nom_original'] = $file->getClientOriginalName();
        }

        if ($request->hasFile('lettre_motivation')) {
            $file = $request->file('lettre_motivation');
            $data['lettre_motivation_path']         = $file->store('candidatures/lettres', 'local');
            $data['lettre_motivation_nom_original'] = $file->getClientOriginalName();
        }

        Candidature::create($data);

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

        if ($candidature->cv_path) {
            Storage::disk('local')->delete($candidature->cv_path);
        }
        if ($candidature->lettre_motivation_path) {
            Storage::disk('local')->delete($candidature->lettre_motivation_path);
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

    /**
     * Téléchargement d'un document (CV ou lettre de motivation) d'une
     * candidature. Accessible au candidat propriétaire ainsi qu'au
     * recruteur propriétaire de l'institution liée à l'offre.
     */
    public function downloadCv(Candidature $candidature): StreamedResponse
    {
        $this->authorizeDocumentAccess($candidature);

        if (! $candidature->aCv()) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $candidature->cv_path,
            $candidature->cv_nom_original ?? 'cv.pdf'
        );
    }

    public function downloadLettre(Candidature $candidature): StreamedResponse
    {
        $this->authorizeDocumentAccess($candidature);

        if (! $candidature->aLettreMotivation()) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $candidature->lettre_motivation_path,
            $candidature->lettre_motivation_nom_original ?? 'lettre-de-motivation.pdf'
        );
    }

    private function authorizeDocumentAccess(Candidature $candidature): void
    {
        $estProprietaire = $candidature->user_id === auth()->id();
        $estRecruteurProprietaire = $candidature->jobOffer->institution->user_id === auth()->id();

        if (! $estProprietaire && ! $estRecruteurProprietaire) {
            abort(403);
        }
    }
}
