@extends('layouts.app')

@section('title', 'Modifier utilisateur')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf @method('PUT')
        @include('users.form', ['user' => $user])
        <button class="btn" type="submit">Enregistrer</button>
    </form>
</div>
@endsection
