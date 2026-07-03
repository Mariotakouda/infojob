<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionRequest;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $validated = $request->validated();

        if ($request->hasFile('document_justificatif')) {
            $file = $request->file('document_justificatif');
            $validated['document_justificatif_path']         = $file->store('institutions/justificatifs', 'local');
            $validated['document_justificatif_nom_original']  = $file->getClientOriginalName();
        }

        // Toute institution — publique ou privée — passe désormais par une
        // vérification admin obligatoire avant de pouvoir publier des offres
        // ou des démarches. Aucun type n'est auto-vérifié à l'inscription.
        $validated['statut_verification'] = 'en_attente';
        $validated['verifiee_at']         = null;
        $validated['verifiee_par']        = null;

        $institution = $request->user()->institutions()->create($validated);

        $message = 'Institution créée avec succès. Elle sera visible avec le badge « Vérifiée » dès validation '
            . 'de votre justificatif par un administrateur. En attendant, vous ne pourrez pas publier d\'offres '
            . 'ni de démarches.';

        return redirect()->route('institutions.show', $institution)->with('success', $message);
    }

    public function edit(Institution $institution): View
    {
        $this->authorizeOwner($institution);

        return view('institutions.edit', compact('institution'));
    }

    public function update(InstitutionRequest $request, Institution $institution): RedirectResponse
    {
        $this->authorizeOwner($institution);

        $validated = $request->validated();

        if ($request->hasFile('document_justificatif')) {
            if ($institution->document_justificatif_path) {
                Storage::disk('local')->delete($institution->document_justificatif_path);
            }
            $file = $request->file('document_justificatif');
            $validated['document_justificatif_path']        = $file->store('institutions/justificatifs', 'local');
            $validated['document_justificatif_nom_original'] = $file->getClientOriginalName();
        }

        $infosIdentiteChangees = $institution->nom !== $validated['nom']
            || $institution->type !== $validated['type']
            || $institution->numero_identification !== $validated['numero_identification'];

        // Toute institution — publique ou privée — repasse par une
        // vérification admin si son identité change ou si elle envoie un
        // nouveau justificatif. Aucun type n'est dispensé de contrôle.
        if (! $institution->estVerifiee() || $infosIdentiteChangees || $request->hasFile('document_justificatif')) {
            $validated['statut_verification'] = 'en_attente';
            $validated['motif_rejet']         = null;
            $validated['verifiee_at']         = null;
            $validated['verifiee_par']        = null;
        }

        $institution->update($validated);

        return redirect()->route('institutions.show', $institution)
            ->with('success', 'Institution mise à jour.');
    }

    public function destroy(Institution $institution): RedirectResponse
    {
        $this->authorizeOwner($institution);

        if ($institution->document_justificatif_path) {
            Storage::disk('local')->delete($institution->document_justificatif_path);
        }

        $institution->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Institution supprimée.');
    }

    /**
     * Téléchargement du justificatif — réservé au propriétaire de
     * l'institution et aux administrateurs (utilisé lors de la modération).
     */
    public function downloadJustificatif(Institution $institution): StreamedResponse
    {
        $estProprietaire = $institution->user_id === auth()->id();
        $estAdmin        = auth()->user()->isAdmin();

        if (! $estProprietaire && ! $estAdmin) {
            abort(403);
        }

        if (! $institution->aDocumentJustificatif()) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $institution->document_justificatif_path,
            $institution->document_justificatif_nom_original ?? 'justificatif.pdf'
        );
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function authorizeOwner(Institution $institution): void
    {
        if ($institution->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}