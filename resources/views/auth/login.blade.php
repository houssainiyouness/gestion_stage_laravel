@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div style="max-width:460px;margin:60px auto;">
    <div class="card">
        <h2>Connexion</h2>
        <p class="muted">Application de gestion de stage intelligente</p>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label style="font-weight:400;">
                    <input type="checkbox" name="remember" style="width:auto;">
                    Se souvenir de moi
                </label>
            </div>

            <button class="btn" type="submit">Se connecter</button>
        </form>

        
    </div>
</div>
@endsection
