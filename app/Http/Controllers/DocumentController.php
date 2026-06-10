<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        $internships = Internship::with('student')
            ->when($user->role === 'etudiant', fn($q) => $q->where('student_id', $user->id))
            ->when($user->role === 'encadrant', fn($q) => $q->where('encadrant_id', $user->id))
            ->get();

        return view('documents.create', compact('internships'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'internship_id' => 'required|exists:internships,id',
            'type' => 'required|in:convention,rapport,autre',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:8192',
        ]);

        $internship = Internship::findOrFail($data['internship_id']);

        $path = $request->file('file')->store('internship-documents', 'public');

        $document = Document::create([
            'internship_id' => $internship->id,
            'student_id' => $internship->student_id,
            'type' => $data['type'],
            'file_path' => $path,
        ]);

        $this->logAction('create', 'documents', $document->id, 'Ajout document stage');

        return redirect()->route('internships.show', $internship)->with('success', 'Document ajouté.');
    }
}
