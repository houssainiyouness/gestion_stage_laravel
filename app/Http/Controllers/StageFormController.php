<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\StageForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StageFormController extends Controller
{
    public function edit(Internship $internship)
    {
        $this->authorizeInternshipAccess($internship, ['etudiant', 'admin_organisme', 'super_admin']);

        $internship->load('student', 'organization', 'encadrant', 'stageForm');

        $stageForm = $internship->stageForm ?? $this->makeDefaultForm($internship);

        return view('stage_forms.edit', compact('internship', 'stageForm'));
    }

    public function update(Request $request, Internship $internship)
    {
        $this->authorizeInternshipAccess($internship, ['etudiant', 'admin_organisme', 'super_admin']);

        $data = $request->validate([
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'telephone_fixe' => 'nullable|string|max:255',
            'telephone_portable' => 'nullable|string|max:255',

            'etablissement_nom' => 'nullable|string|max:255',
            'etablissement_adresse' => 'nullable|string',
            'etablissement_telephone' => 'nullable|string|max:255',
            'etablissement_fax' => 'nullable|string|max:255',

            'specialite' => 'nullable|string|max:255',
            'niveau_etude' => 'nullable|string|max:255',
            'filiere' => 'nullable|string|max:255',

            'stage_type' => 'required|in:nouveau,renouvellement',
            'jours_stage' => 'nullable|string|max:255',
            'horaire_debut' => 'nullable|string|max:50',
            'horaire_fin' => 'nullable|string|max:50',

            'laboratoire_accueil' => 'nullable|string|max:255',
            'maitre_stage' => 'nullable|string|max:255',
            'service_affectation' => 'nullable|string|max:255',
            'encadrants' => 'nullable|string|max:255',
            'sujet_stage' => 'nullable|string',
        ]);

        $data['assurance_ok'] = $request->boolean('assurance_ok');
        $data['convention_ok'] = $request->boolean('convention_ok');
        $data['engagement_ok'] = $request->boolean('engagement_ok');
        $data['submitted_at'] = now();

        $stageForm = StageForm::updateOrCreate(
            ['internship_id' => $internship->id],
            $data
        );

        $this->logAction(
            'update',
            'stage_forms',
            $stageForm->id,
            'Mise à jour des formulaires administratifs du stage.'
        );

        return redirect()
            ->route('internships.show', $internship)
            ->with('success', 'Formulaires enregistrés avec succès.');
    }

    public function print(Internship $internship)
    {
        $this->authorizeInternshipAccess($internship, ['admin_organisme', 'super_admin']);

        $internship->load('student', 'organization', 'encadrant', 'stageForm');

        $stageForm = $internship->stageForm ?? $this->makeDefaultForm($internship);

        return view('stage_forms.print', compact('internship', 'stageForm'));
    }

    private function makeDefaultForm(Internship $internship): StageForm
    {
        $student = $internship->student;

        $nameParts = preg_split('/\s+/', trim($student->name ?? ''), 2);

        return new StageForm([
            'internship_id' => $internship->id,
            'nom' => $nameParts[0] ?? '',
            'prenom' => $nameParts[1] ?? '',
            'email' => $student->email ?? '',
            'telephone_portable' => $student->phone ?? '',
            'stage_type' => 'nouveau',
            'laboratoire_accueil' => $internship->organization->name ?? '',
            'maitre_stage' => $internship->encadrant->name ?? '',
            'service_affectation' => $internship->service ?? '',
            'encadrants' => $internship->encadrant->name ?? '',
            'sujet_stage' => $internship->subject ?? '',
        ]);
    }

    private function authorizeInternshipAccess(Internship $internship, array $roles): void
    {
        $user = Auth::user();

        abort_unless(in_array($user->role, $roles, true), 403);

        if ($user->role === 'etudiant') {
            abort_unless($internship->student_id === $user->id, 403);
        }

        if ($user->role === 'admin_organisme') {
            abort_unless($internship->organization_id === $user->organization_id, 403);
        }
    }
}