<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    // ─── Public ──────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = JobApplication::with('user')
            ->approuves()
            ->latest();

        if ($request->filled('secteur')) {
            $query->parSecteur($request->secteur);
        }

        if ($request->filled('ville')) {
            $query->parVille($request->ville);
        }

        if ($request->boolean('disponible')) {
            $query->disponibles();
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('titre_profil', 'like', "%$q%")
                    ->orWhere('competences', 'like', "%$q%");
            });
        }

        $profils  = $query->paginate(12)->withQueryString();
        $secteurs = JobApplication::approuves()->distinct()->pluck('secteur_activite')->sort();

        return view('job-applications.index', compact('profils', 'secteurs'));
    }

    public function show(JobApplication $jobApplication): View
    {
        if ($jobApplication->statut_moderation !== 'approuve' && ! auth()->user()?->isAdmin()) {
            abort(404);
        }

        $jobApplication->load('user');

        return view('job-applications.show', compact('jobApplication'));
    }

    // ─── Citoyen ─────────────────────────────────────────────────────────────

    public function create(): View
    {
        return view('job-applications.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre_profil'    => ['required', 'string', 'max:150'],
            'secteur_activite'=> ['required', 'string', 'max:100'],
            'competences'     => ['required', 'string', 'max:1000'],
            'ville'           => ['required', 'string', 'max:100'],
            'disponibilite'   => ['boolean'],
        ]);

        $validated['user_id']           = auth()->id();
        $validated['disponibilite']     = $request->boolean('disponibilite');
        $validated['statut_moderation'] = 'en_attente';

        JobApplication::create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Profil soumis, en attente de modération.');
    }

    public function edit(JobApplication $jobApplication): View
    {
        $this->authorizeOwner($jobApplication);

        return view('job-applications.edit', compact('jobApplication'));
    }

    public function update(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        $this->authorizeOwner($jobApplication);

        $validated = $request->validate([
            'titre_profil'    => ['required', 'string', 'max:150'],
            'secteur_activite'=> ['required', 'string', 'max:100'],
            'competences'     => ['required', 'string', 'max:1000'],
            'ville'           => ['required', 'string', 'max:100'],
            'disponibilite'   => ['boolean'],
        ]);

        $validated['disponibilite']     = $request->boolean('disponibilite');
        $validated['statut_moderation'] = 'en_attente'; // re-modération à chaque modif

        $jobApplication->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Profil mis à jour, en attente de re-modération.');
    }

    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $this->authorizeOwner($jobApplication);

        $jobApplication->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Profil supprimé.');
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function authorizeOwner(JobApplication $jobApplication): void
    {
        if ($jobApplication->user_id !== auth()->id()) {
            abort(403);
        }
    }
}