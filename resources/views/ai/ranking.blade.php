@extends('layouts.app')
 
@section('title', 'Classement IA')
 
@section('content')
<div class="card">
    <h3>Classement IA - {{ $offer->title }}</h3>
 
    <p>
        <strong>Organisme :</strong> {{ $offer->organization->name ?? '-' }}
    </p>
 
    <p>
        <strong>Compétences demandées :</strong> {{ $offer->required_skills ?: 'Non précisées' }}
    </p>
 
    <a href="{{ route('ia.index') }}" class="btn small secondary">
        Retour aux offres
    </a>
    <form method="POST" action="{{ route('ia.analyze-all', $offer) }}" style="display:inline-block; margin-left:8px;">
        @csrf
        <button type="submit" class="btn small">
            Analyser tous les candidats
        </button>
    </form>
</div>
 
<div class="card">
    <table>
        <thead>
        <tr>
            <th>Rang</th>
            <th>Candidat</th>
            <th>Correspondance IA</th>
            <th>Recommandation</th>
            <th>Résumé IA</th>
            <th>Actions</th>
        </tr>
        </thead>
 
        <tbody>
        @php $rank = 1; @endphp
        @forelse($applications as $application)
            <tr>
                <td>
                    @if(!is_null($application->ai_match_rate))
                        <strong>#{{ $rank++ }}</strong>
                    @else
                        <span class="muted">-</span>
                    @endif
                </td>
 
                <td>
                    <strong>{{ $application->candidate_name ?: $application->student->name }}</strong><br>
                    <span class="muted">{{ $application->candidate_email ?: $application->student->email }}</span>
                </td>
 
                <td>
                    @if(!is_null($application->ai_match_rate))
                        <span class="badge {{ $application->ai_match_rate >= 70 ? 'green' : ($application->ai_match_rate >= 45 ? 'orange' : 'red') }}">
                            {{ $application->ai_match_rate }}%
                        </span>
                    @else
                        <span class="muted">Non analysé</span>
                    @endif
                </td>
 
                <td>
                    <strong>{{ $application->ai_recommendation ?: '-' }}</strong>
                </td>
 
                <td>
                    {{ $application->ai_summary ?: '-' }}
 
                    @if($application->ai_keywords_found || $application->ai_keywords_missing || $application->ai_recommendations)
                        <br><br>
                        <span class="muted">Mots-clés trouvés :</span>
                        {{ $application->ai_keywords_found ?: '-' }}
                        <br>
                        <span class="muted">Mots-clés manquants :</span>
                        {{ $application->ai_keywords_missing ?: '-' }}
                        <br>
                        <span class="muted">Recommandations :</span>
                        {{ $application->ai_recommendations ?: '-' }}
                    @endif
                </td>
 
                <td class="actions">
                    @if($application->cv_path)
                        <a class="btn small secondary"
                           href="{{ asset('storage/'.$application->cv_path) }}"
                           target="_blank">
                            Voir CV
                        </a>
                    @endif
 
                    <form method="POST" action="{{ route('applications.analyze-ai', $application) }}">
                        @csrf
                        <button class="btn small" type="submit">
                            {{ $application->ai_analyzed_at ? 'Relancer IA' : 'Analyser IA' }}
                        </button>
                    </form>
 
                    @if($application->status === 'en_attente')
                        <form method="POST" action="{{ route('applications.accept', $application) }}">
                            @csrf
                            <button class="btn small green" type="submit"
                                onclick="return confirm('Accepter cette candidature ?')">
                                Accepter
                            </button>
                        </form>
 
                        <form method="POST" action="{{ route('applications.refuse', $application) }}">
                            @csrf
                            <button class="btn small red" type="submit"
                                onclick="return confirm('Refuser cette candidature ?')">
                                Refuser
                            </button>
                        </form>
 
                    @elseif($application->status === 'acceptee')
                        <span class="badge green">Acceptée</span>
 
                    @elseif($application->status === 'refusee')
                        <span class="badge red">Refusée</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Aucune candidature pour cette offre.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection