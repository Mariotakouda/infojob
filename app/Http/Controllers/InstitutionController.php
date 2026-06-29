<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionRequest;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstitutionController extends Controller
{
    // ─── Public ──────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Institution::withCount(['procedures', 'jobOffers'])
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('ville')) {
            $query->where('ville', 'like', '%' . $request->ville . '%');
        }

        if ($request->filled('q')) {
            $query->where('nom', 'like', '%' . $request->q . '%');
        }

        $institutions = $query->paginate(12)->withQueryString();

        return view('institutions.index', compact('institutions'));
    }

    public function show(Institution $institution): View
    {
        $institution->load([
            'procedures.requirements',
            'jobOffers' => fn ($q) => $q->publiees()->latest(),
        ]);

        return view('institutions.show', compact('institution'));
    }

    // ─── Recruteur ───────────────────────────────────────────────────────────

    public function create(): View
    {
        return view('institutions.create');
    }

    public function store(InstitutionRequest $request): RedirectResponse
    {
        $institution = $request->user()->institutions()->create($request->validated());

        return redirect()->route('institutions.show', $institution)
            ->with('success', 'Institution créée avec succès.');
    }

    public function edit(Institution $institution): View
    {
        $this->authorizeOwner($institution);

        return view('institutions.edit', compact('institution'));
    }

    public function update(InstitutionRequest $request, Institution $institution): RedirectResponse
    {
        $this->authorizeOwner($institution);

        $institution->update($request->validated());

        return redirect()->route('institutions.show', $institution)
            ->with('success', 'Institution mise à jour.');
    }

    public function destroy(Institution $institution): RedirectResponse
    {
        $this->authorizeOwner($institution);

        $institution->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Institution supprimée.');
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function authorizeOwner(Institution $institution): void
    {
        if ($institution->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}
