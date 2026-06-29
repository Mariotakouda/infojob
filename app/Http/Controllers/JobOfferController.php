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

        if ($request->filled('lieu')) {
            $query->where('lieu', 'like', '%' . $request->lieu . '%');
        }

        $offres = $query->paginate(12)->withQueryString();

        return view('job-offers.index', compact('offres'));
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
        $institutions = auth()->user()->institutions()->get();
        return view('job-offers.create', compact('institutions'));
    }

    public function store(StoreJobOfferRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Vérifie que l'institution appartient au recruteur connecté
        $institution = Institution::findOrFail($validated['institution_id']);
        Gate::authorize('manage', $institution);

        $validated['statut'] = 'en_attente';

        JobOffer::create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Offre créée. Elle sera publiée après modération.');
    }

    public function edit(JobOffer $jobOffer): View
    {
        Gate::authorize('manage', $jobOffer->institution);
        $institutions = auth()->user()->institutions()->get();
        return view('job-offers.edit', compact('jobOffer', 'institutions'));
    }

    public function update(UpdateJobOfferRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        Gate::authorize('manage', $jobOffer->institution);

        $jobOffer->update($request->validated());

        return redirect()->route('dashboard')->with('success', 'Offre mise à jour.');
    }

    public function destroy(JobOffer $jobOffer): RedirectResponse
    {
        Gate::authorize('manage', $jobOffer->institution);
        $jobOffer->delete();

        return redirect()->route('dashboard')->with('success', 'Offre supprimée.');
    }
}
