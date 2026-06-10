<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Soutenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoutenanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Soutenance::with([
            'internship.student',
            'internship.organization',
            'internship.encadrant'
        ])->latest();

        if ($user->role === 'admin_organisme') {
            $query->whereHas('internship', function ($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            });
        }

        if ($user->role === 'encadrant') {
            $query->whereHas('internship', function ($q) use ($user) {
                $q->where('encadrant_id', $user->id);
            });
        }

        if ($user->role === 'etudiant') {
            $query->whereHas('internship', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            });
        }

        // super_admin : aucune condition, il voit toutes les soutenances

        $soutenances = $query->paginate(10);

        return view('soutenances.index', compact('soutenances'));
    }

    public function create()
    {
        $user = Auth::user();

        $query = Internship::with(['student', 'organization', 'encadrant'])
            ->where('status', 'en_cours')
            ->whereDoesntHave('soutenance');

        if ($user->role === 'admin_organisme') {
            $query->where('organization_id', $user->organization_id);
        }

        if ($user->role === 'encadrant') {
            $query->where('encadrant_id', $user->id);
        }

        // super_admin : aucune condition, il voit tous les stages en cours sans soutenance

        $internships = $query->get();

        return view('soutenances.create', compact('internships'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'internship_id' => 'required|exists:internships,id|unique:soutenances,internship_id',
            'date_soutenance' => 'required|date',
            'jury' => 'required|string',
            'final_note' => 'nullable|numeric|min:0|max:20',
            'resultat' => 'required|in:en_attente,valide,non_valide',
        ], [
            'internship_id.required' => 'Le stage est obligatoire.',
            'date_soutenance.required' => 'La date de soutenance est obligatoire.',
            'jury.required' => 'Le jury est obligatoire.',
            'resultat.required' => 'Le résultat est obligatoire.',
        ]);

        $user = Auth::user();

        $internship = Internship::findOrFail($data['internship_id']);

        if ($user->role === 'admin_organisme' && $internship->organization_id !== $user->organization_id) {
            abort(403);
        }

        if ($user->role === 'encadrant' && $internship->encadrant_id !== $user->id) {
            abort(403);
        }

        if ($user->role === 'etudiant') {
            abort(403);
        }

        $soutenance = Soutenance::create($data);

        $this->logAction(
            'create',
            'soutenances',
            $soutenance->id,
            'Planification soutenance'
        );

        return redirect()
            ->route('soutenances.index')
            ->with('success', 'Soutenance enregistrée.');
    }
}