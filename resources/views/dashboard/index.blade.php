@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid">

    {{-- Étudiant : voir seulement le nombre des offres --}}
    @if($user->role === 'etudiant')

        <div class="stat">
            <span>Offres disponibles</span>
            <strong>{{ $stats['offers'] }}</strong>
        </div>

    {{-- Super Admin --}}
    @elseif($user->role === 'super_admin')

        <div class="stat">
            <span>Utilisateurs</span>
            <strong>{{ $stats['users'] }}</strong>
        </div>

        <div class="stat">
            <span>Organismes</span>
            <strong>{{ $stats['organizations'] }}</strong>
        </div>

        <div class="stat">
            <span>Offres</span>
            <strong>{{ $stats['offers'] }}</strong>
        </div>

        <div class="stat">
            <span>Candidatures</span>
            <strong>{{ $stats['applications'] }}</strong>
        </div>

        <div class="stat">
            <span>Stages</span>
            <strong>{{ $stats['internships'] }}</strong>
        </div>

        <div class="stat">
            <span>Soutenances</span>
            <strong>{{ $stats['soutenances'] }}</strong>
        </div>

        <div class="stat">
            <span>Taux acceptation</span>
            <strong>{{ $stats['acceptance_rate'] }}%</strong>
        </div>

        <div class="stat">
            <span>Taux réussite</span>
            <strong>{{ $stats['success_rate'] }}%</strong>
        </div>

    {{-- Admin Organisme --}}
    @elseif($user->role === 'admin_organisme')

        <div class="stat">
            <span>Offres</span>
            <strong>{{ $stats['offers'] }}</strong>
        </div>

        <div class="stat">
            <span>Candidatures</span>
            <strong>{{ $stats['applications'] }}</strong>
        </div>

        <div class="stat">
            <span>Stages</span>
            <strong>{{ $stats['internships'] }}</strong>
        </div>

        <div class="stat">
            <span>Soutenances</span>
            <strong>{{ $stats['soutenances'] }}</strong>
        </div>

        <div class="stat">
            <span>Taux acceptation</span>
            <strong>{{ $stats['acceptance_rate'] }}%</strong>
        </div>

        <div class="stat">
            <span>Taux réussite</span>
            <strong>{{ $stats['success_rate'] }}%</strong>
        </div>

    {{-- Encadrant --}}
    @elseif($user->role === 'encadrant')

        <div class="stat">
            <span>Stages suivis</span>
            <strong>{{ $stats['internships'] }}</strong>
        </div>

        <div class="stat">
            <span>Soutenances</span>
            <strong>{{ $stats['soutenances'] }}</strong>
        </div>

    @endif

</div>


<div class="card">
    <h3>Bienvenue {{ $user->name }}</h3>

    
</div>
@endsection
