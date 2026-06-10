@extends('layouts.app')

@section('title', 'Détail offre')

@section('content')
<div class="card">
    <h3>{{ $offer->title }}</h3>
    <p class="muted">{{ $offer->organization->name ?? '-' }} — {{ $offer->location ?? '-' }}</p>
    <p>{{ $offer->description }}</p>
    <h4>Compétences demandées</h4>
<p>{{ $offer->required_skills ?: 'Non défini' }}</p>
    <p>
    <strong>Type :</strong> {{ $offer->type ?? 'Non défini' }}
</p>

    <h4>Profil demandé</h4>
    <p>{{ $offer->profile_required ?? '-' }}</p>

    @if(auth()->user()->role === 'etudiant')
        <a class="btn green" href="{{ route('applications.create', $offer) }}">Postuler</a>
    @endif
</div>

@if(in_array(auth()->user()->role, ['super_admin','admin_organisme']))
<div class="card">
    <h3>Candidatures reçues</h3>
    <table>
        <thead>
        <tr>
            <th>Étudiant</th>
            <th>Statut</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @forelse($offer->applications as $application)
            <tr>
                <td>{{ $application->student->name }}</td>
                <td>{{ $application->status }}</td>
                <td>{{ $application->created_at->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Aucune candidature.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection
