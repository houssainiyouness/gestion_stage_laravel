@extends('layouts.app')

@section('title', 'Détail stage')

@section('content')
<div class="card">
    <h3>{{ $internship->subject }}</h3>
    <p><strong>Étudiant :</strong> {{ $internship->student->name ?? '-' }}</p>
    <p><strong>Organisme :</strong> {{ $internship->organization->name ?? '-' }}</p>
    <p><strong>Encadrant :</strong> {{ $internship->encadrant->name ?? 'Non affecté' }}</p>
    <p><strong>Service :</strong> {{ $internship->service ?? '-' }}</p>
    <p><strong>Statut :</strong> {{ $internship->status }}</p>
</div>

<div class="card">
    <h3>Formulaires administratifs</h3>

  

    @if($internship->stageForm)
        <p><strong>Statut :</strong> <span class="badge green">Remplis</span></p>
        <p>
            <strong>Dernière modification :</strong>
            {{ $internship->stageForm->updated_at ? $internship->stageForm->updated_at->format('d/m/Y H:i') : '-' }}
        </p>
    @else
        <p><strong>Statut :</strong> <span class="badge orange">Non remplis</span></p>
        <p class="muted">Les informations ne sont pas encore complétées par l’étudiant.</p>
    @endif

    <div class="actions">
        @if(in_array(auth()->user()->role, ['etudiant', 'admin_organisme', 'super_admin']))
            <a class="btn small green" href="{{ route('internships.forms.edit', $internship) }}">
                Remplir / modifier
            </a>
        @endif

        @if(in_array(auth()->user()->role, ['admin_organisme', 'super_admin']))
            <a class="btn small secondary" href="{{ route('internships.forms.print', $internship) }}" target="_blank">
                Imprimer les formulaires
            </a>
        @endif
    </div>
    </div>
    <div class="card">
    <h3>Documents</h3>
    <table>
        <thead><tr><th>Type</th><th>Fichier</th><th>Date</th></tr></thead>
        <tbody>
        @forelse($internship->documents as $document)
            <tr>
                <td>{{ $document->type }}</td>
                <td><a href="{{ asset('storage/'.$document->file_path) }}" target="_blank">Ouvrir</a></td>
                <td>{{ $document->created_at->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Aucun document.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Suivis</h3>
    <table>
        <thead><tr><th>Date</th><th>Encadrant</th><th>Avancement</th><th>Description</th></tr></thead>
        <tbody>
        @forelse($internship->suivis as $suivi)
            <tr>
                <td>{{ $suivi->follow_up_date->format('d/m/Y') }}</td>
                <td>{{ $suivi->encadrant->name ?? '-' }}</td>
                <td>{{ $suivi->progress_percentage }}%</td>
                <td>{{ $suivi->description }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Aucun suivi.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Évaluations</h3>
    <table>
        <thead><tr><th>Encadrant</th><th>Note</th><th>Commentaire</th></tr></thead>
        <tbody>
        @forelse($internship->evaluations as $evaluation)
            <tr>
                <td>{{ $evaluation->encadrant->name ?? '-' }}</td>
                <td>{{ $evaluation->note ?? '-' }}</td>
                <td>{{ $evaluation->commentaire ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Aucune évaluation.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Soutenance</h3>
    @if($internship->soutenance)
        <p><strong>Date :</strong> {{ $internship->soutenance->date_soutenance->format('d/m/Y H:i') }}</p>
        <p><strong>Jury :</strong> {{ $internship->soutenance->jury }}</p>
        <p><strong>Note finale :</strong> {{ $internship->soutenance->final_note ?? '-' }}</p>
        <p><strong>Résultat :</strong> {{ $internship->soutenance->resultat }}</p>
    @else
        <p>Aucune soutenance planifiée.</p>
    @endif
</div>
@endsection
