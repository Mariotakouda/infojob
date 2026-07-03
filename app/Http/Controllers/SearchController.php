<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Recherche rapide depuis la page d'accueil : une offre d'emploi, une
     * démarche, un artisan ou une institution, le tout en une seule requête.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));

        $offres = $procedures = $artisans = $institutions = collect();

        if ($q !== '') {
            $offres = JobOffer::with('institution')
                ->publiees()
                ->where(function ($query) use ($q) {
                    $query->where('titre', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('metier', 'like', "%{$q}%")
                        ->orWhere('lieu', 'like', "%{$q}%");
                })
                ->latest()
                ->limit(6)
                ->get();

            $procedures = Procedure::with('institution')
                ->where(function ($query) use ($q) {
                    $query->where('titre', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                })
                ->latest()
                ->limit(6)
                ->get();

            $artisans = JobApplication::with('user')
                ->approuves()
                ->where(function ($query) use ($q) {
                    $query->where('titre_profil', 'like', "%{$q}%")
                        ->orWhere('secteur_activite', 'like', "%{$q}%")
                        ->orWhere('competences', 'like', "%{$q}%")
                        ->orWhere('ville', 'like', "%{$q}%");
                })
                ->latest()
                ->limit(6)
                ->get();

            $institutions = Institution::where(function ($query) use ($q) {
                    $query->where('nom', 'like', "%{$q}%")
                        ->orWhere('ville', 'like', "%{$q}%");
                })
                ->withCount(['procedures', 'jobOffers'])
                ->limit(6)
                ->get();
        }

        $total = $offres->count() + $procedures->count() + $artisans->count() + $institutions->count();

        return view('search.index', compact('q', 'offres', 'procedures', 'artisans', 'institutions', 'total'));
    }
}
