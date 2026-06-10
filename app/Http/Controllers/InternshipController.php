<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Internship::with('student', 'organization', 'encadrant')->latest();

        if ($user->role === 'admin_organisme') {
            $query->where('organization_id', $user->organization_id);
        }

        if ($user->role === 'encadrant') {
            $query->where('encadrant_id', $user->id);
        }

        if ($user->role === 'etudiant') {
            $query->where('student_id', $user->id);
        }

        $internships = $query->paginate(10);

        return view('internships.index', compact('internships'));
    }

    public function show(Internship $internship)
    {
        $internship->load('student', 'organization', 'encadrant', 'documents', 'suivis.encadrant', 'evaluations.encadrant', 'soutenance');
        return view('internships.show', compact('internship'));
    }

    public function edit(Internship $internship)
    {
        $encadrants = User::where('role', 'encadrant')
            ->when($internship->organization_id, fn($q) => $q->where('organization_id', $internship->organization_id))
            ->orderBy('name')
            ->get();

        return view('internships.edit', compact('internship', 'encadrants'));
    }

    public function update(Request $request, Internship $internship)
    {
        $data = $request->validate([
            'encadrant_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:en_attente,en_cours,termine,annule',
        ]);

        $internship->update($data);
        $this->logAction('update', 'internships', $internship->id, 'Modification stage');

        return redirect()->route('internships.show', $internship)->with('success', 'Stage modifié.');
    }
}
