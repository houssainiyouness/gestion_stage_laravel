<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Suivi;
use Illuminate\Http\Request;

class SuiviController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        $internships = Internship::with(['student', 'organization', 'encadrant'])
            ->when($user->role === 'encadrant', function ($q) use ($user) {
                $q->where('encadrant_id', $user->id);
            })
            ->when($user->role === 'admin_organisme', function ($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            })
            ->get();

        return view('suivis.create', compact('internships'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'internship_id' => 'required|exists:internships,id',
            'description' => 'required|string',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'follow_up_date' => 'required|date',
        ], [
            'internship_id.required' => 'Le stage est obligatoire.',
            'description.required' => 'La description du suivi est obligatoire.',
            'progress_percentage.required' => 'Le pourcentage d’avancement est obligatoire.',
            'follow_up_date.required' => 'La date du suivi est obligatoire.',
        ]);

        $data['encadrant_id'] = auth()->id();

        Suivi::create($data);

        return redirect()
            ->route('internships.index')
            ->with('success', 'Suivi ajouté avec succès.');
    }
}