<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
public function create()
{
    $user = Auth::user();

    $internships = Internship::with(['student', 'organization'])
        ->when($user->role === 'encadrant', function ($q) use ($user) {
            $q->where('encadrant_id', $user->id);
        })
        ->when($user->role === 'admin_organisme', function ($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })
        ->get();

    return view('evaluations.create', compact('internships'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'internship_id' => 'required|exists:internships,id',
        'note' => 'nullable|numeric|min:0|max:20',
        'commentaire' => 'nullable|string',
    ]);

    $user = Auth::user();

    $internship = Internship::findOrFail($data['internship_id']);

    if ($user->role === 'encadrant' && $internship->encadrant_id !== $user->id) {
        abort(403, 'Vous ne pouvez pas évaluer ce stagiaire.');
    }

    if ($user->role === 'admin_organisme' && $internship->organization_id !== $user->organization_id) {
        abort(403, 'Vous ne pouvez pas évaluer un stagiaire d’un autre organisme.');
    }

    $data['encadrant_id'] = Auth::id();

    $evaluation = Evaluation::create($data);

    $this->logAction('create', 'evaluations', $evaluation->id, 'Ajout évaluation');

    return redirect()
        ->route('internships.show', $data['internship_id'])
        ->with('success', 'Évaluation ajoutée.');
}
}