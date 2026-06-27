<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProcedureController extends Controller
{
    // ─── Public ──────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Procedure::with('institution')->latest();

        if ($request->filled('institution')) {
            $query->where('institution_id', $request->institution);
        }

        if ($request->filled('q')) {
            $query->where('titre', 'like', '%' . $request->q . '%');
        }

        $procedures   = $query->paginate(12)->withQueryString();
        $institutions = Institution::orderBy('nom')->get(['id', 'nom']);

        return view('procedures.index', compact('procedures', 'institutions'));
    }

    public function show(Procedure $procedure): View
    {
        $procedure->load(['institution', 'requirements' => fn ($q) => $q->orderByDesc('est_obligatoire')]);

        return view('procedures.show', compact('procedure'));
    }

    // ─── Recruteur ───────────────────────────────────────────────────────────

    public function create(): View
    {
        $institutions = auth()->user()->institutions()->get(['id', 'nom']);

        return view('procedures.create', compact('institutions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'titre'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
            'cout'           => ['nullable', 'integer', 'min:0'],
            'delai'          => ['nullable', 'string', 'max:100'],
            'lieu_depot'     => ['nullable', 'string', 'max:255'],
            'lien_en_ligne'  => ['nullable', 'url', 'max:255'],
        ]);

        // Vérifie que l'institution appartient au recruteur
        $institution = auth()->user()->institutions()->findOrFail($validated['institution_id']);

        $procedure = $institution->procedures()->create($validated);

        return redirect()->route('procedures.show', $procedure)
            ->with('success', 'Démarche créée avec succès.');
    }

    public function edit(Procedure $procedure): View
    {
        $this->authorizeOwner($procedure);

        $institutions = auth()->user()->institutions()->get(['id', 'nom']);

        return view('procedures.edit', compact('procedure', 'institutions'));
    }

    public function update(Request $request, Procedure $procedure): RedirectResponse
    {
        $this->authorizeOwner($procedure);

        $validated = $request->validate([
            'titre'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'cout'          => ['nullable', 'integer', 'min:0'],
            'delai'         => ['nullable', 'string', 'max:100'],
            'lieu_depot'    => ['nullable', 'string', 'max:255'],
            'lien_en_ligne' => ['nullable', 'url', 'max:255'],
        ]);

        $procedure->update($validated);

        return redirect()->route('procedures.show', $procedure)
            ->with('success', 'Démarche mise à jour.');
    }

    public function destroy(Procedure $procedure): RedirectResponse
    {
        $this->authorizeOwner($procedure);

        $procedure->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Démarche supprimée.');
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function authorizeOwner(Procedure $procedure): void
    {
        $ownerIds = auth()->user()->institutions()->pluck('id');

        if (! $ownerIds->contains($procedure->institution_id) && ! auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}