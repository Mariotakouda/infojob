<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RequirementController extends Controller
{
    public function store(Request $request, Procedure $procedure): RedirectResponse
    {
        // Vérifie que la procédure appartient au recruteur connecté
        $ownerIds = auth()->user()->institutions()->pluck('id');
        if (! $ownerIds->contains($procedure->institution_id)) {
            abort(403);
        }

        $validated = $request->validate([
            'libelle'         => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:500'],
            'est_obligatoire' => ['boolean'],
        ]);

        $validated['est_obligatoire'] = $request->boolean('est_obligatoire');

        $procedure->requirements()->create($validated);

        return back()->with('success', 'Pièce ajoutée.');
    }

    public function destroy(Procedure $procedure, Requirement $requirement): RedirectResponse
    {
        $ownerIds = auth()->user()->institutions()->pluck('id');
        if (! $ownerIds->contains($procedure->institution_id)) {
            abort(403);
        }

        if ($requirement->procedure_id !== $procedure->id) {
            abort(404);
        }

        $requirement->delete();

        return back()->with('success', 'Pièce supprimée.');
    }
}