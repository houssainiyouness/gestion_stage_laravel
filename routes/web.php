<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SoutenanceController;
use App\Http\Controllers\SuiviController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StageFormController;

/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Routes protégées
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:super_admin')->group(function () {
        Route::resource('organizations', OrganizationController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Offres de stage
    |--------------------------------------------------------------------------
    */

    Route::get('/offers', [OfferController::class, 'index'])
        ->middleware('role:etudiant,admin_organisme,super_admin')
        ->name('offers.index');

    Route::middleware('role:admin_organisme,super_admin')->group(function () {
        Route::get('/offers/create', [OfferController::class, 'create'])
            ->name('offers.create');

        Route::post('/offers', [OfferController::class, 'store'])
            ->name('offers.store');

        Route::get('/offers/{offer}/edit', [OfferController::class, 'edit'])
            ->name('offers.edit');

        Route::put('/offers/{offer}', [OfferController::class, 'update'])
            ->name('offers.update');

        Route::delete('/offers/{offer}', [OfferController::class, 'destroy'])
            ->name('offers.destroy');
    });

    Route::get('/offers/{offer}', [OfferController::class, 'show'])
        ->middleware('role:etudiant,admin_organisme,super_admin')
        ->name('offers.show');

    /*
    |--------------------------------------------------------------------------
    | Candidatures
    |--------------------------------------------------------------------------
    */

    Route::get('/offers/{offer}/apply', [ApplicationController::class, 'create'])
        ->middleware('role:etudiant,super_admin')
        ->name('applications.create');

    Route::post('/offers/{offer}/apply', [ApplicationController::class, 'store'])
        ->middleware('role:etudiant,super_admin')
        ->name('applications.store');

    Route::get('/applications', [ApplicationController::class, 'index'])
        ->middleware('role:etudiant,admin_organisme,super_admin')
        ->name('applications.index');

    Route::post('/applications/{application}/accept', [ApplicationController::class, 'accept'])
        ->middleware('role:admin_organisme,super_admin')
        ->name('applications.accept');

    Route::post('/applications/{application}/refuse', [ApplicationController::class, 'refuse'])
        ->middleware('role:admin_organisme,super_admin')
        ->name('applications.refuse');

/*
|--------------------------------------------------------------------------
| Module IA - Analyse des CV
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin_organisme,super_admin')->group(function () {
    Route::get('/ia', [AiController::class, 'index'])
        ->name('ia.index');

    Route::get('/ia/offres/{offer}/classement', [AiController::class, 'ranking'])
        ->name('ia.ranking');

    Route::post('/applications/{application}/analyse-ia', [AiController::class, 'analyze'])
        ->name('applications.analyze-ai');

    Route::post('/ia/offres/{offer}/analyse-tous', [AiController::class, 'analyzeAll'])
        ->name('ia.analyze-all');
});
    /*
    |--------------------------------------------------------------------------
    | Stages
    |--------------------------------------------------------------------------
    */

    Route::get('/internships', [InternshipController::class, 'index'])
        ->middleware('role:etudiant,admin_organisme,encadrant,super_admin')
        ->name('internships.index');

    Route::get('/internships/{internship}', [InternshipController::class, 'show'])
        ->middleware('role:etudiant,admin_organisme,encadrant,super_admin')
        ->name('internships.show');

    Route::middleware('role:admin_organisme,super_admin')->group(function () {
        Route::get('/internships/{internship}/edit', [InternshipController::class, 'edit'])
            ->name('internships.edit');

        Route::put('/internships/{internship}', [InternshipController::class, 'update'])
            ->name('internships.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    Route::resource('documents', DocumentController::class)
        ->only(['create', 'store'])
        ->middleware('role:etudiant,super_admin');

    /*
    |--------------------------------------------------------------------------
    | Suivi
    |--------------------------------------------------------------------------
    */

    Route::resource('suivis', SuiviController::class)
        ->only(['create', 'store'])
        ->middleware('role:encadrant,admin_organisme,super_admin');

    /*
    |--------------------------------------------------------------------------
    | Évaluations
    |--------------------------------------------------------------------------
    */

    Route::resource('evaluations', EvaluationController::class)
        ->only(['create', 'store'])
        ->middleware('role:encadrant,admin_organisme,super_admin');

    /*
    |--------------------------------------------------------------------------
    | Soutenances
    |--------------------------------------------------------------------------
    */

    Route::get('/soutenances', [SoutenanceController::class, 'index'])
        ->middleware('role:etudiant,admin_organisme,encadrant,super_admin')
        ->name('soutenances.index');

    Route::middleware('role:admin_organisme,super_admin,encadrant')->group(function () {
        Route::get('/soutenances/create', [SoutenanceController::class, 'create'])
            ->name('soutenances.create');

        Route::post('/soutenances', [SoutenanceController::class, 'store'])
            ->name('soutenances.store');
    });
Route::get('/test-mail', function () {
    $user = \App\Models\User::where('role', 'etudiant')->first();
    $offer = \App\Models\Offer::first();
    $user->notify(new \App\Notifications\CandidatureAcceptee($offer));
    return 'Email envoyé !';
});

Route::get('/test-mail-log', function() {
    $user = \App\Models\User::where('role', 'etudiant')->first();
    $offer = \App\Models\Offer::first();
    $user->notify(new \App\Notifications\CandidatureAcceptee($offer));
    return "Email log généré !";

});
Route::middleware('role:etudiant,admin_organisme,super_admin')->group(function () {
    Route::get('/internships/{internship}/formulaires', [StageFormController::class, 'edit'])
        ->name('internships.forms.edit');

    Route::put('/internships/{internship}/formulaires', [StageFormController::class, 'update'])
        ->name('internships.forms.update');
});

Route::get('/internships/{internship}/formulaires/imprimer', [StageFormController::class, 'print'])
    ->middleware('role:admin_organisme,super_admin')
    ->name('internships.forms.print');
});
