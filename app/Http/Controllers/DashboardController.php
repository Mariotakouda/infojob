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

        if ($user->role === 'admin') {
            return redirect()->route('admin.index');
        }

        if ($user->role === 'recruteur') {
            $user->load([
                'institutions.jobOffers.candidatures.user',  // candidatures reçues
                'institutions.procedures',                    // démarches publiées
            ]);
        } else {
            $user->load([
                'jobApplications',
                'candidatures.jobOffer.institution',
            ]);
        }

        return match ($user->role) {
            'recruteur' => view('dashboards.recruteur', compact('user')),
            default     => view('dashboards.citoyen', compact('user')),
        };
    }
}