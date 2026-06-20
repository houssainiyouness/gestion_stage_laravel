@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php

    $icon = [
        'briefcase' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><path d="M3 12h18"/></svg>',
        'user' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>',
        'building' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v16"/><path d="M9 21v-5h3v5"/><path d="M8 7h1M12 7h1M8 11h1M12 11h1M19 21V10h1a2 2 0 0 1 2 2v9"/></svg>',
        'inbox' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="m5.45 5.11-3.25 7.27A2 2 0 0 0 4.03 15H20a2 2 0 0 0 1.83-2.62l-3.25-7.27A2 2 0 0 0 16.75 4H7.25a2 2 0 0 0-1.8 1.11Z"/></svg>',
        'folder' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg>',
        'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
        'check' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>',
        'star' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9Z"/></svg>',
    ];

    $role = $user->role;
    $roleText = [
        'super_admin' => 'SUPER ADMIN',
        'admin_organisme' => 'ADMIN ORGANISME',
        'encadrant' => 'ENCADRANT',
        'etudiant' => 'ÉTUDIANT',
    ][$role] ?? strtoupper($role);

    $welcomeTitle = $role === 'etudiant'
        ? 'Bienvenue, '.$user->name
        : 'Bienvenue, '.$user->name;

    $welcomeText = $role === 'etudiant'
        ? 'Consultez les offres disponibles, suivez vos candidatures et accédez aux informations de votre stage.'
        : 'Voici un aperçu de votre activité sur la plateforme de gestion de stage.';

    // Les raccourcis d'action sont volontairement retirés du bloc d'accueil.
    // Tous les cas d'utilisation restent accessibles dans la navigation principale,
    // pour éviter les doublons entre le dashboard et le menu.
    $heroActions = [];

    $statsCards = [];
    if($role === 'etudiant') {
        $statsCards = [
            ['label' => 'Offres disponibles', 'value' => $stats['offers'] ?? 0, 'icon' => 'briefcase', 'tone' => 'blue'],
        ];
    } elseif($role === 'super_admin') {
        $statsCards = [
            ['label' => 'Utilisateurs', 'value' => $stats['users'] ?? 0, 'icon' => 'user', 'tone' => 'purple'],
            ['label' => 'Organismes', 'value' => $stats['organizations'] ?? 0, 'icon' => 'building', 'tone' => 'blue'],
            ['label' => 'Offres', 'value' => $stats['offers'] ?? 0, 'icon' => 'briefcase', 'tone' => 'blue'],
            ['label' => 'Candidatures', 'value' => $stats['applications'] ?? 0, 'icon' => 'inbox', 'tone' => 'cyan'],
            ['label' => 'Stages', 'value' => $stats['internships'] ?? 0, 'icon' => 'folder', 'tone' => 'cyan'],
            ['label' => 'Soutenances', 'value' => $stats['soutenances'] ?? 0, 'icon' => 'calendar', 'tone' => 'purple'],
            ['label' => 'Taux acceptation', 'value' => ($stats['acceptance_rate'] ?? 0).'%', 'icon' => 'check', 'tone' => 'cyan'],
            ['label' => 'Taux réussite', 'value' => ($stats['success_rate'] ?? 0).'%', 'icon' => 'star', 'tone' => 'blue'],
        ];
    } elseif($role === 'admin_organisme') {
        $statsCards = [
            ['label' => 'Offres', 'value' => $stats['offers'] ?? 0, 'icon' => 'briefcase', 'tone' => 'blue'],
            ['label' => 'Candidatures', 'value' => $stats['applications'] ?? 0, 'icon' => 'inbox', 'tone' => 'cyan'],
            ['label' => 'Stages', 'value' => $stats['internships'] ?? 0, 'icon' => 'folder', 'tone' => 'cyan'],
            ['label' => 'Soutenances', 'value' => $stats['soutenances'] ?? 0, 'icon' => 'calendar', 'tone' => 'purple'],
            ['label' => 'Taux acceptation', 'value' => ($stats['acceptance_rate'] ?? 0).'%', 'icon' => 'check', 'tone' => 'cyan'],
            ['label' => 'Taux réussite', 'value' => ($stats['success_rate'] ?? 0).'%', 'icon' => 'star', 'tone' => 'blue'],
        ];
    } elseif($role === 'encadrant') {
        $statsCards = [
            ['label' => 'Stages suivis', 'value' => $stats['internships'] ?? 0, 'icon' => 'folder', 'tone' => 'cyan'],
            ['label' => 'Soutenances', 'value' => $stats['soutenances'] ?? 0, 'icon' => 'calendar', 'tone' => 'purple'],
        ];
    }
@endphp

<section class="dashboard-layout">
    <div class="welcome-card">
        <div class="welcome-content">
            <div class="welcome-role">{{ $roleText }}</div>
            <h2 class="welcome-title">{{ $welcomeTitle }}</h2>
            <p class="welcome-text">{{ $welcomeText }}</p>
            @if(count($heroActions))
                <div class="hero-actions">
                    @foreach($heroActions as $action)
                        <a class="btn {{ $action['style'] === 'outline' ? 'btn-hero-outline' : '' }}" href="{{ route($action['route']) }}">
                            {!! $icon[$action['icon']] !!}
                            {{ $action['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <svg class="hero-visual" viewBox="0 0 470 250" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="330" cy="78" r="70" fill="white" fill-opacity=".055"/>
            <circle cx="92" cy="205" r="54" fill="#3B82F6" fill-opacity=".08"/>
            <path d="M70 180C143 175 142 110 215 116C277 121 280 72 358 88" stroke="#22D3EE" stroke-width="3" stroke-linecap="round" stroke-dasharray="2 12"/>
            <circle cx="70" cy="180" r="8" fill="#60A5FA"/>
            <circle cx="215" cy="116" r="7" fill="white" fill-opacity=".9"/>
            <circle cx="358" cy="88" r="7" fill="white" fill-opacity=".9"/>
            <rect x="152" y="160" width="50" height="42" rx="8" fill="white" fill-opacity=".92"/>
            <path d="M165 160V147C165 141 170 137 176 137H178C184 137 189 141 189 147V160" stroke="#0B1F3F" stroke-width="4" stroke-linecap="round"/>
            <path d="M152 181H202" stroke="#0B1F3F" stroke-width="3" opacity=".35"/>
            <rect x="278" y="110" width="43" height="60" rx="8" fill="white" fill-opacity=".92"/>
            <path d="M289 128H310M289 143H306" stroke="#0B1F3F" stroke-width="4" stroke-linecap="round" opacity=".25"/>
            <path d="M302 157l6 6 10-13" stroke="#60A5FA" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M360 45l48-20 48 20-48 20-48-20Z" stroke="white" stroke-width="4" stroke-linejoin="round" opacity=".92"/>
            <path d="M382 55v24c17 11 35 11 52 0V55" stroke="white" stroke-width="4" stroke-linecap="round" opacity=".92"/>
            <circle cx="405" cy="88" r="16" fill="white"/>
            <path d="M398 88l5 5 10-12" stroke="#60A5FA" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="115" cy="118" r="4" fill="#60A5FA"/>
            <circle cx="243" cy="178" r="5" fill="white" fill-opacity=".32"/>
            <circle cx="195" cy="96" r="4" fill="white" fill-opacity=".45"/>
        </svg>
    </div>

    <aside class="workflow-card">
        <h3>Processus de gestion</h3>
        <div class="workflow-step">
            <span class="step-number">1</span>
            <div>
                <strong>Gestion des offres</strong>
                <span>Les offres sont publiées, consultées et mises à jour.</span>
            </div>
        </div>
        <div class="workflow-step">
            <span class="step-number">2</span>
            <div>
                <strong>Suivi des candidatures</strong>
                <span>Les dossiers sont traités avec un suivi clair des statuts.</span>
            </div>
        </div>
        <div class="workflow-step">
            <span class="step-number">3</span>
            <div>
                <strong>Encadrement et évaluation</strong>
                <span>Les étapes de suivi, d’évaluation et de soutenance restent centralisées.</span>
            </div>
        </div>
    </aside>
</section>

<section class="stats-grid">
    @foreach($statsCards as $card)
        <article class="stat">
            <span class="stat-icon {{ $card['tone'] }}">{!! $icon[$card['icon']] !!}</span>
            <div>
                <span>{{ $card['label'] }}</span>
                <strong>{{ $card['value'] }}</strong>
            </div>
        </article>
    @endforeach
</section>
@endsection
