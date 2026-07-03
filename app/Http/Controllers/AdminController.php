<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModererProfilRequest;
use App\Http\Requests\PublierOffreRequest;
use App\Http\Requests\VerifierInstitutionRequest;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\User;
use App\Models\Institution;
use App\Models\Candidature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users'           => User::count(),
            'institutions'    => Institution::count(),
            'institutions_attente' => Institution::enAttenteVerification()->count(),
            'offres_attente'  => JobOffer::where('statut', 'en_attente')->count(),
            'offres_publiees' => JobOffer::where('statut', 'publie')->count(),
            'profils_attente' => JobApplication::where('statut_moderation', 'en_attente')->count(),
            'candidatures'    => Candidature::count(),
        ];

        $profilsEnAttente = JobApplication::with('user')
            ->where('statut_moderation', 'en_attente')
            ->latest()
            ->take(20)
            ->get();

        $offresEnAttente = JobOffer::with('institution')
            ->where('statut', 'en_attente')
            ->latest()
            ->take(20)
            ->get();

        $institutionsEnAttente = Institution::with('user')
            ->enAttenteVerification()
            ->latest()
            ->take(20)
            ->get();

        return view('admin.index', compact('stats', 'profilsEnAttente', 'offresEnAttente', 'institutionsEnAttente'));
    }

    /**
     * Liste paginée des utilisateurs, avec recherche et filtre par rôle.
     * Rendue dans un partial pour pouvoir être rafraîchie de façon isolée
     * (utilisée aussi bien en chargement complet qu'en pagination/recherche).
     */
    public function users(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $role   = $request->query('role', '');

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, ['admin', 'recruteur', 'citoyen'], true), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.partials.users-table', [
            'users'  => $users,
            'search' => $search,
            'role'   => $role,
        ]);
    }

    public function modererProfil(ModererProfilRequest $request, JobApplication $jobApplication): RedirectResponse
    {
        $validated = $request->validated();

        $jobApplication->update($validated);

        $label = $validated['statut_moderation'] === 'approuve' ? 'approuvé' : 'rejeté';

        return back()->with('success', "Profil {$label} avec succès.");
    }

    public function publierOffre(PublierOffreRequest $request, JobOffer $jobOffer): RedirectResponse
    {
        $validated = $request->validated();

        $jobOffer->update($validated);

        $label = match ($validated['statut']) {
            'publie'      => 'publiée',
            'expire'      => 'marquée expirée',
            'en_attente'  => 'remise en attente',
        };

        return back()->with('success', "Offre {$label}.");
    }

    /**
     * Décision de l'admin sur le justificatif d'une institution (mairie,
     * préfecture, ministère... ou entreprise privée / startup).
     */
    public function verifierInstitution(VerifierInstitutionRequest $request, Institution $institution): RedirectResponse
    {
        $validated = $request->validated();

        $institution->update([
            'statut_verification' => $validated['statut_verification'],
            'motif_rejet'          => $validated['statut_verification'] === 'rejetee' ? $validated['motif_rejet'] : null,
            'verifiee_at'          => $validated['statut_verification'] === 'verifiee' ? now() : null,
            'verifiee_par'         => $validated['statut_verification'] === 'verifiee' ? auth()->id() : null,
        ]);

        // Dès qu'une institution est vérifiée, ses offres déjà en attente
        // (créées avant vérification, ou remises en attente entre-temps)
        // sont publiées automatiquement : la vérification de l'institution
        // est désormais l'unique porte de modération.
        if ($validated['statut_verification'] === 'verifiee') {
            $institution->jobOffers()->where('statut', 'en_attente')->update(['statut' => 'publie']);
        }

        $label = match ($validated['statut_verification']) {
            'verifiee' => 'vérifiée',
            'rejetee'  => 'refusée',
            default    => 'remise en attente',
        };

        return back()->with('success', "Institution {$label}.");
    }

    public function supprimerUser(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }
}