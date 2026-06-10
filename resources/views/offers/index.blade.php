@extends('layouts.app')

@section('title', 'Offres de stage')

@section('content')
<div class="card">
    <div class="actions" style="justify-content:space-between;">
        <h3>Liste des offres</h3>
        @if(in_array(auth()->user()->role, ['super_admin','admin_organisme']))
            <a class="btn" href="{{ route('offers.create') }}">Nouvelle offre</a>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Organisme</th>
                <th>Lieu</th>
                <th>Statut</th>
                <th>Période</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($offers as $offer)
            <tr>
                <td>{{ $offer->title }}</td>
                <td>{{ $offer->organization->name ?? '-' }}</td>
                <td>{{ $offer->location ?? '-' }}</td>
                <td><span class="badge blue">{{ $offer->status }}</span></td>
                <td>{{ optional($offer->start_date)->format('d/m/Y') }} → {{ optional($offer->end_date)->format('d/m/Y') }}</td>
                <td class="actions">
                    <a class="btn small secondary" href="{{ route('offers.show', $offer) }}">Voir</a>
                    @if(auth()->user()->role === 'etudiant')
                        <a class="btn small green" href="{{ route('applications.create', $offer) }}">Postuler</a>
                    @endif
                    @if(in_array(auth()->user()->role, ['super_admin','admin_organisme']))
                        <a class="btn small orange" href="{{ route('offers.edit', $offer) }}">Modifier</a>
                        <form method="POST" action="{{ route('offers.destroy', $offer) }}" onsubmit="return confirm('Supprimer cette offre ?')">
                            @csrf @method('DELETE')
                            <button class="btn small red" type="submit">Supprimer</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Aucune offre trouvée.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
