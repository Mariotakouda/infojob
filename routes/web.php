<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\CandidatureController;

// ─── Authentification (invités uniquement) ───────────────────────────────────

Route::middleware('guest')->group(function () {

    Route::get('/inscription', [RegisterController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store']);

    Route::get('/connexion', [LoginController::class, 'create'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store']);

});

Route::post('/deconnexion', [LoginController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

// ─── Page d'accueil publique ─────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─── Consultation publique (sans connexion) ──────────────────────────────────

Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
Route::get('/institutions/{institution:slug}', [InstitutionController::class, 'show'])->name('institutions.show');

Route::get('/demarches', [ProcedureController::class, 'index'])->name('procedures.index');
Route::get('/demarches/{procedure:slug}', [ProcedureController::class, 'show'])->name('procedures.show');

Route::get('/offres', [JobOfferController::class, 'index'])->name('job-offers.index');
Route::get('/offres/{jobOffer}', [JobOfferController::class, 'show'])->name('job-offers.show');

Route::get('/artisans', [JobApplicationController::class, 'index'])->name('job-applications.index');
Route::get('/artisans/{jobApplication}', [JobApplicationController::class, 'show'])->name('job-applications.show');

// ─── Espace connecté ─────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');

    // ── Profils / Vitrines artisan (citoyen uniquement) ──────────────────────

    Route::middleware('role:citoyen')->prefix('mon-profil')->name('job-applications.')->group(function () {
        Route::get('/creer', [JobApplicationController::class, 'create'])->name('create');
        Route::post('/', [JobApplicationController::class, 'store'])->name('store');
        Route::get('/{jobApplication}/modifier', [JobApplicationController::class, 'edit'])->name('edit');
        Route::put('/{jobApplication}', [JobApplicationController::class, 'update'])->name('update');
        Route::delete('/{jobApplication}', [JobApplicationController::class, 'destroy'])->name('destroy');
    });

    // ── Candidatures (citoyen uniquement) ────────────────────────────────────

    Route::middleware('role:citoyen')->prefix('candidatures')->name('candidatures.')->group(function () {
        Route::post('/{jobOffer}', [CandidatureController::class, 'store'])->name('store');
        Route::delete('/{candidature}', [CandidatureController::class, 'destroy'])->name('destroy');
        Route::get('/mes-candidatures', [CandidatureController::class, 'index'])->name('index');
    });

    // ── Gestion recruteur (recruteur uniquement) ──────────────────────────────

    Route::middleware('role:recruteur')->group(function () {

        // Institutions du recruteur
        Route::prefix('mes-institutions')->name('institutions.')->group(function () {
            Route::get('/creer', [InstitutionController::class, 'create'])->name('create');
            Route::post('/', [InstitutionController::class, 'store'])->name('store');
            Route::get('/{institution:slug}/modifier', [InstitutionController::class, 'edit'])->name('edit');
            Route::put('/{institution:slug}', [InstitutionController::class, 'update'])->name('update');
            Route::delete('/{institution:slug}', [InstitutionController::class, 'destroy'])->name('destroy');
        });

        // Procédures (liées à une institution)
        Route::prefix('demarches')->name('procedures.')->group(function () {
            Route::get('/creer', [ProcedureController::class, 'create'])->name('create');
            Route::post('/', [ProcedureController::class, 'store'])->name('store');
            Route::get('/{procedure:slug}/modifier', [ProcedureController::class, 'edit'])->name('edit');
            Route::put('/{procedure:slug}', [ProcedureController::class, 'update'])->name('update');
            Route::delete('/{procedure:slug}', [ProcedureController::class, 'destroy'])->name('destroy');

            // Requirements (nested sous procedure)
            Route::post('/{procedure:slug}/requirements', [RequirementController::class, 'store'])->name('requirements.store');
            Route::delete('/{procedure:slug}/requirements/{requirement}', [RequirementController::class, 'destroy'])->name('requirements.destroy');
        });

        // Offres d'emploi / chantiers
        Route::prefix('mes-offres')->name('job-offers.')->group(function () {
            Route::get('/creer', [JobOfferController::class, 'create'])->name('create');
            Route::post('/', [JobOfferController::class, 'store'])->name('store');
            Route::get('/{jobOffer}/modifier', [JobOfferController::class, 'edit'])->name('edit');
            Route::put('/{jobOffer}', [JobOfferController::class, 'update'])->name('update');
            Route::delete('/{jobOffer}', [JobOfferController::class, 'destroy'])->name('destroy');
        });

        // Gestion des candidatures reçues
        Route::patch('/candidatures/{candidature}/statut', [CandidatureController::class, 'updateStatut'])
            ->name('candidatures.update-statut');
    });

    // ── Administration ────────────────────────────────────────────────────────

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::patch('/job-applications/{jobApplication}/moderer', [AdminController::class, 'modererProfil'])->name('moderer-profil');
        Route::patch('/job-offers/{jobOffer}/publier', [AdminController::class, 'publierOffre'])->name('publier-offre');
        Route::delete('/users/{user}', [AdminController::class, 'supprimerUser'])->name('supprimer-user');
    });

});
