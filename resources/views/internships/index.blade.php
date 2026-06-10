@extends('layouts.app')

@section('title', 'Stages')

@section('content')
<div class="card">
    <table>
        <thead>
        <tr>
            <th>Sujet</th>
            <th>Étudiant</th>
            <th>Organisme</th>
            <th>Encadrant</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($internships as $internship)
            <tr>
                <td>{{ $internship->subject }}</td>
                <td>{{ $internship->student->name ?? '-' }}</td>
                <td>{{ $internship->organization->name ?? '-' }}</td>
                <td>{{ $internship->encadrant->name ?? 'Non affecté' }}</td>
                <td><span class="badge blue">{{ $internship->status }}</span></td>
                <td class="actions">
                    <a class="btn small secondary" href="{{ route('internships.show', $internship) }}">Voir</a>
                    @if(in_array(auth()->user()->role, ['super_admin','admin_organisme']))
                        <a class="btn small orange" href="{{ route('internships.edit', $internship) }}">Modifier</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Aucun stage.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $internships->links() }}
</div>
@endsection
