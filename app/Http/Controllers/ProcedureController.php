<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcedureRequest;
use App\Http\Requests\UpdateProcedureRequest;
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

    public function store(StoreProcedureRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Vérifie que l'institution appartient au recruteur
        $institution = auth()->user()->institutions()->findOrFail($validated['institution_id']);

        $procedure = $institution->procedures()->create($validated);

        // ─── Sauvegarde des pièces ────────────────────────────────────────
        $this->syncRequirements($procedure, $request->input('pieces', []));

        return redirect()->route('procedures.show', $procedure)
            ->with('success', 'Démarche créée avec succès.');
    }

    public function edit(Procedure $procedure): View
    {
        $this->authorizeOwner($procedure);

        $procedure->load('requirements');

        $institutions = auth()->user()->institutions()->get(['id', 'nom']);

        return view('procedures.edit', compact('procedure', 'institutions'));
    }

    public function update(UpdateProcedureRequest $request, Procedure $procedure): RedirectResponse
    {
        $this->authorizeOwner($procedure);

        $procedure->update($request->validated());

        // ─── Resynchronise les pièces (delete + recreate) ─────────────────
        $this->syncRequirements($procedure, $request->input('pieces', []));

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

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Supprime toutes les pièces existantes et recrée depuis le tableau $pieces.
     */
    private function syncRequirements(Procedure $procedure, array $pieces): void
    {
        $procedure->requirements()->delete();

        foreach ($pieces as $piece) {
            $libelle = trim($piece['libelle'] ?? '');

            if ($libelle === '') {
                continue; // ignore les lignes vides
            }

            $procedure->requirements()->create([
                'libelle'         => $libelle,
                'est_obligatoire' => ! empty($piece['est_obligatoire']),
            ]);
        }
    }

    private function authorizeOwner(Procedure $procedure): void
    {
        $ownerIds = auth()->user()->institutions()->pluck('id');

        if (! $ownerIds->contains($procedure->institution_id) && ! auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}