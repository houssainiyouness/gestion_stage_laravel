@extends('layouts.app')

@section('title', 'Soutenances')

@section('content')
<div class="card">
    <div class="actions" style="justify-content:space-between;">
        <h3>Liste des soutenances</h3>
        @if(in_array(auth()->user()->role, ['super_admin','admin_organisme','encadrant']))
            <a class="btn" href="{{ route('soutenances.create') }}">Planifier</a>
        @endif
    </div>

    <table>
        <thead>
        <tr>
            <th>Étudiant</th>
            <th>Sujet</th>
            <th>Date</th>
            <th>Jury</th>
            <th>Note</th>
            <th>Résultat</th>
        </tr>
        </thead>
        <tbody>
        @forelse($soutenances as $soutenance)
            <tr>
                <td>{{ $soutenance->internship->student->name ?? '-' }}</td>
                <td>{{ $soutenance->internship->subject ?? '-' }}</td>
                <td>{{ $soutenance->date_soutenance->format('d/m/Y H:i') }}</td>
                <td>{{ $soutenance->jury }}</td>
                <td>{{ $soutenance->final_note ?? '-' }}</td>
                <td><span class="badge blue">{{ $soutenance->resultat }}</span></td>
            </tr>
        @empty
            <tr><td colspan="6">Aucune soutenance.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $soutenances->links() }}
</div>
@endsection
