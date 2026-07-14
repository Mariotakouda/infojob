<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobOfferRequest;
use App\Http\Requests\UpdateJobOfferRequest;
use App\Models\JobOffer;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;

class JobOfferController extends Controller
{
    // ── Public ───────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = JobOffer::with('institution')
            ->publiees()
            ->latest();

        if ($request->filled('type')) {
            $query->parType($request->type);
        }

        if ($request->filled('metier')) {
            $query->parMetier($request->metier);
        }

        if ($request->filled('lieu')) {
            $query->where('lieu', 'like', '%' . $request->lieu . '%');
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('titre', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('metier', 'like', "%$q%")
                    ->orWhere('lieu', 'like', "%$q%");
            });
        }

        $offres = $query->paginate(12)->withQueryString();
        $metiers = JobOffer::METIERS;

        return view('job-offers.index', compact('offres', 'metiers'));
    }

    public function show(JobOffer $jobOffer): View
    {
        $jobOffer->load('institution', 'candidatures');

        $dejaPostule = auth()->check()
            ? $jobOffer->candidatures->where('user_id', auth()->id())->isNotEmpty()
            : false;

        return view('job-offers.show', compact('jobOffer', 'dejaPostule'));
    }

    // ── Recruteur ─────────────────────────────────────────────────────────────

    public function create(): View
    {
        $institutions = auth()->user()->institutions()->verifiees()->get();
        $metiers = JobOffer::METIERS;
        return view('job-offers.create', compact('institutions', 'metiers'));
    }

    public function store(StoreJobOfferRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Vérifie que l'institution appartient au recruteur connecté
        $institution = Institution::findOrFail($validated['institution_id']);
        Gate::authorize('manage', $institution);

        if (! $institution->estVerifiee()) {
            return back()->withInput()->with(
                'error',
                'Votre institution doit d\'abord être vérifiée par un administrateur avant de pouvoir publier une offre.'
            );
        }

        // L'institution est déjà vérifiée à ce stade (contrôle ci-dessus) :
        // ses offres se publient donc automatiquement, sans étape de
        // modération supplémentaire par offre.
        $validated['statut'] = 'publie';

        if ($request->hasFile('affiche')) {
            $validated['affiche'] = $request->file('affiche')->store('job-offers/affiches', 'public');
        }

        JobOffer::create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Offre publiée avec succès.');
    }

    public function edit(JobOffer $jobOffer): View
    {
        Gate::authorize('manage', $jobOffer->institution);
        $institutions = auth()->user()->institutions()->get();
        $metiers = JobOffer::METIERS;
        return view('job-offers.edit', compact('jobOffer', 'institutions', 'metiers'));
    }

    public function update(UpdateJobOfferRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        Gate::authorize('manage', $jobOffer->institution);

        $validated = $request->validated();

        if ($request->hasFile('affiche')) {
            if ($jobOffer->affiche) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($jobOffer->affiche);
            }
            $validated['affiche'] = $request->file('affiche')->store('job-offers/affiches', 'public');
        } elseif ($request->boolean('supprimer_affiche') && $jobOffer->affiche) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($jobOffer->affiche);
            $validated['affiche'] = null;
        }

        $jobOffer->update($validated);

        return redirect()->route('dashboard')->with('success', 'Offre mise à jour.');
    }

    public function destroy(JobOffer $jobOffer): RedirectResponse
    {
        Gate::authorize('manage', $jobOffer->institution);

        if ($jobOffer->affiche) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($jobOffer->affiche);
        }

        $jobOffer->delete();

        return redirect()->route('dashboard')->with('success', 'Offre supprimée.');
    }

    /**
     * Liste de tous les candidats ayant postulé à une offre, avec accès aux
     * documents (CV / lettre de motivation) et export PDF de la liste.
     */
    public function candidats(JobOffer $jobOffer): View
    {
        Gate::authorize('manage', $jobOffer->institution);

        $jobOffer->load('institution');

        $candidatures = $jobOffer->candidatures()
            ->with('user')
            ->latest()
            ->get();

        return view('job-offers.candidats', compact('jobOffer', 'candidatures'));
    }

    /**
     * Génère et télécharge la liste des candidats d'une offre au format PDF.
     */
    public function candidatsPdf(JobOffer $jobOffer)
    {
        Gate::authorize('manage', $jobOffer->institution);

        $jobOffer->load('institution');

        $candidatures = $jobOffer->candidatures()
            ->with('user')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('job-offers.candidats-pdf', compact('jobOffer', 'candidatures'))
            ->setPaper('a4', 'portrait');

        $nomFichier = 'candidats-' . \Illuminate\Support\Str::slug($jobOffer->titre) . '.pdf';

        return $pdf->download($nomFichier);
    }
}