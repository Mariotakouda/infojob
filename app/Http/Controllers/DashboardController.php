<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Les admins disposent d'un panneau dédié et plus complet.
        if ($user->role === 'admin') {
            return redirect()->route('admin.index');
        }

        $user->load([
            'institutions.jobOffers',
            'jobApplications',
            'candidatures.jobOffer.institution',
        ]);

        return match ($user->role) {
            'recruteur' => view('dashboards.recruteur', compact('user')),
            default     => view('dashboards.citoyen', compact('user')),
        };
    }
}
