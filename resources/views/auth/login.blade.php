@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
@php
    $capIcon = '<svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10L12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c3.5 2.5 8.5 2.5 12 0v-5"/><path d="M22 10v6"/></svg>';
    $shieldIcon = '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>';
    $userIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>';
    $lockIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';
    $eyeIcon = '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>';
@endphp

<div class="login-page">
    <header class="login-topbar">
        <div class="login-brand">
            <span class="login-logo">{!! $capIcon !!}</span>
            <span>
                <span class="login-brand-title">Gestion des stages</span>
                <span class="login-brand-sub">Portail académique</span>
            </span>
        </div>
        <div class="secure-chip">
            {!! $shieldIcon !!}
            <span>Accès sécurisé</span>
        </div>
    </header>

    <svg class="login-bg-icon cap" viewBox="0 0 260 180" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M235 57 130 10 25 57l105 47 105-47Z"/>
        <path d="M70 77v56c35 26 85 26 120 0V77"/>
        <path d="M235 57v66"/>
        <path d="M235 123c-15 18-15 34 0 48 15-14 15-30 0-48Z"/>
    </svg>

    <svg class="login-bg-icon path" viewBox="0 0 560 540" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <path d="M40 280c70-120 180-30 215-110 45-100 140-94 250-42" stroke-dasharray="11 14"/>
        <path d="M128 250h108v76H128z" rx="14"/>
        <path d="M160 250v-35h44v35"/>
        <path d="M375 325h96v128h-96z" rx="12"/>
        <path d="M398 356h48M398 385h48M398 414h31"/>
        <path d="M420 70h82v88h-82z" rx="12"/>
        <path d="M440 100h42M440 125h42"/>
        <circle cx="40" cy="280" r="12" fill="currentColor" stroke="none"/>
        <circle cx="505" cy="128" r="12" fill="currentColor" stroke="none"/>
    </svg>

    <main class="login-center">
        <section class="login-card">
            <div class="login-card-head">
                <div class="login-logo">{!! $capIcon !!}</div>
                <h1 class="login-title">Connexion</h1>
            </div>

            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label>Adresse e-mail</label>
                    <div class="input-wrap">
                        {!! $userIcon !!}
                        <input type="email" name="email" value="{{ old('email') }}"  required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-wrap">
                        {!! $lockIcon !!}
                        <input type="password" name="password" required>
                        <span class="input-action"></span>
                    </div>
                </div>

                <div class="login-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a class="forgot-link" href="#">Mot de passe oublié ?</a>
                </div>

                <button class="btn login-submit" type="submit">Se connecter</button>
            </form>
        </section>
    </main>
</div>
@endsection
