@extends('layouts.app')

@section('title', 'Candidatures')
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
@section('content')
<div class="card">
    <table>
        <thead>
        <tr>
            <th>Offre</th>
            <th>Étudiant</th>
            <th>Organisme</th>
            <th>Statut</th>
            <th>Documents</th>
            <th>Analyse IA</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @forelse($applications as $application)
            <tr>
                <td>{{ $application->offer->title }}</td>

                <td>{{ $application->student->name }}</td>

                <td>{{ $application->offer->organization->name ?? '-' }}</td>

                <td>
                    <span class="badge {{ $application->status === 'acceptee' ? 'green' : ($application->status === 'refusee' ? 'red' : 'orange') }}">
                        {{ $application->status }}
                    </span>
                </td>

                <td>
                    @if($application->cv_path)
                        <a href="{{ asset('storage/'.$application->cv_path) }}" target="_blank">
                            CV
                        </a>
                    @endif

                    @if($application->motivation_letter_path)
                        | <a href="{{ asset('storage/'.$application->motivation_letter_path) }}" target="_blank">
                            Lettre
                        </a>
                    @endif
                </td>

                <td>
                    @if(!is_null($application->ai_match_rate))
                        <span class="badge {{ $application->ai_match_rate >= 70 ? 'green' : ($application->ai_match_rate >= 45 ? 'orange' : 'red') }}">
                            {{ $application->ai_match_rate }}%
                        </span>
                        <br>
                        <span class="muted">{{ $application->ai_recommendation }}</span>
                    @else
                        <span class="muted">Non analysée</span>
                    @endif
                </td>

                <td class="actions">
                    @if(in_array(auth()->user()->role, ['admin_organisme', 'super_admin']))
                        <form method="POST" action="{{ route('applications.analyze-ai', $application) }}">
                            @csrf
                            <button class="btn small" type="submit">
                                {{ $application->ai_analyzed_at ? 'Relancer IA' : 'Analyser IA' }}
                            </button>
                        </form>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin_organisme', 'super_admin']) && $application->status === 'en_attente')
                        <form method="POST" action="{{ route('applications.accept', $application) }}">
                            @csrf
                            <button class="btn small green" type="submit">
                                Accepter
                            </button>
                        </form>

                        <form method="POST" action="{{ route('applications.refuse', $application) }}">
                            @csrf
                            <button class="btn small red" type="submit">
                                Refuser
                            </button>
                        </form>
                    @endif

                    @if(!in_array(auth()->user()->role, ['admin_organisme', 'super_admin']))
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Aucune candidature.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection