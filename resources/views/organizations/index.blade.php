@extends('layouts.app')

@section('title', 'Organismes')

@section('content')
<div class="card">
    <div class="actions" style="justify-content:space-between;">
        <h3>Organismes</h3>
        <a class="btn" href="{{ route('organizations.create') }}">Nouvel organisme</a>
    </div>

    <table>
        <thead><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($organizations as $organization)
            <tr>
                <td>{{ $organization->name }}</td>
                <td>{{ $organization->email ?? '-' }}</td>
                <td>{{ $organization->phone ?? '-' }}</td>
                <td class="actions">
                    <a class="btn small orange" href="{{ route('organizations.edit', $organization) }}">Modifier</a>
                    <form method="POST" action="{{ route('organizations.destroy', $organization) }}">
                        @csrf @method('DELETE')
                        <button class="btn small red" type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Aucun organisme.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $organizations->links() }}
</div>
@endsection
