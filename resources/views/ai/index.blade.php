@extends('layouts.app')

@section('title', 'Analyse IA des candidatures')

@section('content')
<div class="card">
    <h3>Module IA - Classement des candidatures par offre</h3>
    <p class="muted">
        Choisissez une offre pour voir les candidats postulés et les classer selon le taux de correspondance IA.
    </p>
</div>

<div class="card">
    <table>
        <thead>
        <tr>
            <th>Offre</th>
            <th>Organisme</th>
            <th>Compétences demandées</th>
            <th>Nombre de candidatures</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        @forelse($offers as $offer)
            <tr>
                <td>
                    <strong>{{ $offer->title }}</strong><br>
                    <span class="muted">{{ $offer->profile_required ?? '-' }}</span>
                </td>

                <td>{{ $offer->organization->name ?? '-' }}</td>

                <td>{{ $offer->required_skills ?: '-' }}</td>

                <td>
                    <span class="badge blue">{{ $offer->applications_count }}</span>
                </td>

                <td>
                    <a class="btn small" href="{{ route('ia.ranking', $offer) }}">
                        Voir classement
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucune offre trouvée.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection