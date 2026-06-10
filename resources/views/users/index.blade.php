@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<div class="card">
    <div class="actions" style="justify-content:space-between;">
        <h3>Utilisateurs</h3>
        <a class="btn" href="{{ route('users.create') }}">Nouvel utilisateur</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Organisme</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge blue">{{ $user->role }}</span>
                </td>
                <td>{{ $user->organization->name ?? '-' }}</td>
                <td class="actions">
                    <a class="btn small orange" href="{{ route('users.edit', $user) }}">
                        Modifier
                    </a>

                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn small red" type="submit">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucun utilisateur.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        @if ($users->hasPages())
    <div class="custom-pagination">
        @if ($users->onFirstPage())
            <span class="page-disabled">Précédent</span>
        @else
            <a href="{{ $users->previousPageUrl() }}">Précédent</a>
        @endif

        @for ($i = 1; $i <= $users->lastPage(); $i++)
            @if ($i == $users->currentPage())
                <span class="page-active">{{ $i }}</span>
            @else
                <a href="{{ $users->url($i) }}">{{ $i }}</a>
            @endif
        @endfor

        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}">Suivant</a>
        @else
            <span class="page-disabled">Suivant</span>
        @endif
    </div>
@endif
    </div>
</div>
@endsection
