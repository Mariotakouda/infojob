<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\User;
use App\Models\Institution;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users'           => User::count(),
            'institutions'    => Institution::count(),
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

        $derniersUsers = User::latest()->take(10)->get();

        return view('admin.index', compact('stats', 'profilsEnAttente', 'offresEnAttente', 'derniersUsers'));
    }

    public function modererProfil(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        $validated = $request->validate([
            'statut_moderation' => ['required', 'in:approuve,rejete'],
        ]);

        $jobApplication->update($validated);

        $label = $validated['statut_moderation'] === 'approuve' ? 'approuvé' : 'rejeté';

        return back()->with('success', "Profil {$label} avec succès.");
    }

    public function publierOffre(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        $validated = $request->validate([
            'statut' => ['required', 'in:publie,expire,en_attente'],
        ]);

        $jobOffer->update($validated);

        $label = match ($validated['statut']) {
            'publie'      => 'publiée',
            'expire'      => 'marquée expirée',
            'en_attente'  => 'remise en attente',
        };

        return back()->with('success', "Offre {$label}.");
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