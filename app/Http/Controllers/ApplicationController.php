<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Document;
use App\Models\Internship;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CandidatureAcceptee;


class ApplicationController extends Controller
{
public function index()
{
    $user = auth()->user();

    $query = Application::with([
        'student',
        'offer.organization',
        'internship.organization'
    ]);

    if ($user->role === 'etudiant') {
        $query->where('student_id', $user->id);
    }

    if ($user->role === 'admin_organisme') {
        $query->whereHas('offer', function ($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        });
    }

$applications = $query->orderByDesc('id')->get();
    return view('applications.index', compact('applications'));
}

    public function create(Offer $offer)
    {
        return view('applications.create', compact('offer'));
    }

  public function store(Request $request, Offer $offer)
{
    $user = auth()->user();

    // Vérifier si l'étudiant a déjà un stage sur la même période
    $hasOverlap = Internship::where('student_id', $user->id)
        ->whereIn('status', ['en_attente', 'en_cours'])
        ->where(function ($query) use ($offer) {
            $query->whereDate('start_date', '<=', $offer->end_date)
                  ->whereDate('end_date', '>=', $offer->start_date);
        })
        ->exists();

    if ($hasOverlap) {
        return back()->with('error', 'Vous ne pouvez pas postuler à cette offre, car ses dates chevauchent un autre stage.');
    }

    // Vérifier si l'étudiant a déjà postulé à cette offre
    $alreadyApplied = Application::where('student_id', $user->id)
        ->where('offer_id', $offer->id)
        ->exists();

    if ($alreadyApplied) {
        return back()->with('error', 'Vous avez déjà postulé à cette offre.');
    }
$data = $request->validate([
    'cv_path' => 'required|file|mimes:pdf,doc,docx|max:2048',
    'motivation_letter_path' => 'required|file|mimes:pdf,doc,docx|max:2048',
], [
    'cv_path.required' => 'Le CV est obligatoire.',
    'cv_path.mimes' => 'Le CV doit être un fichier PDF, DOC ou DOCX.',
    'motivation_letter_path.required' => 'La lettre de motivation est obligatoire.',
    'motivation_letter_path.mimes' => 'La lettre doit être un fichier PDF, DOC ou DOCX.',
]);

$cvPath = $request->file('cv_path')->store('cvs', 'public');
$letterPath = $request->file('motivation_letter_path')->store('lettres', 'public');

Application::create([
    'offer_id' => $offer->id,
    'student_id' => $user->id,
    'status' => 'en_attente',
    'cv_path' => $cvPath,
    'motivation_letter_path' => $letterPath,
]);

    return redirect()
        ->route('applications.index')
        ->with('success', 'Candidature envoyée avec succès.');
}

public function accept(Application $application)
{
    $application->load(['offer', 'internship']);
    $hasOverlap = Internship::where('student_id', $application->student_id)
    ->whereIn('status', ['en_attente', 'en_cours'])
    ->where('application_id', '!=', $application->id)
    ->where(function ($query) use ($application) {
        $query->whereDate('start_date', '<=', $application->offer->end_date)
              ->whereDate('end_date', '>=', $application->offer->start_date);
    })
    ->exists();

if ($hasOverlap) {
    return back()->with('error', 'Impossible d’accepter cette candidature : l’étudiant a déjà un stage sur cette période.');
}

    $application->update([
        'status' => 'acceptee'
    ]);
if ($application->internship) {
    // Si le stage existe déjà, on le met en cours
    $application->internship->update([
        'status' => 'en_cours',
    ]);

    $internship = $application->internship;

    $this->logAction(
        'update',
        'internships',
        $application->internship->id,
        'Stage mis en cours après acceptation de la candidature.'
    );
} else {
        // Si le stage n'existe pas, on le crée directement en cours
        $internship = Internship::create([
            'application_id' => $application->id,
            'student_id' => $application->student_id,
            'organization_id' => $application->offer->organization_id,
            'encadrant_id' => null,
            'subject' => $application->offer->title,
            'service' => null,
            'start_date' => $application->offer->start_date,
            'end_date' => $application->offer->end_date,
            'status' => 'en_cours',
        ]);

        $this->logAction(
            'create',
            'internships',
            $internship->id,
            'Stage créé automatiquement après acceptation.'
        );
    }
$application->student->notify(new CandidatureAcceptee($application->offer, $internship));   

 

    $this->logAction(
        'accept',
        'applications',
        $application->id,
        'Candidature acceptée.'
    );

    return back()->with('success', 'Candidature acceptée et stage mis en cours.');
}

public function refuse(Application $application)
{
    $application->load('internship');

    $application->update([
        'status' => 'refusee'
    ]);

    if ($application->internship) {
        $application->internship->update([
            'status' => 'refuse',
        ]);

        $this->logAction(
            'update',
            'internships',
            $application->internship->id,
            'Stage refusé après refus de la candidature.'
        );
    }

    $this->logAction(
        'refuse',
        'applications',
        $application->id,
        'Candidature refusée.'
    );

    return back()->with('success', 'Candidature refusée.');
}
}
