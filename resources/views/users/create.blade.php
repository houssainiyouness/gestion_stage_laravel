@extends('layouts.app')

@section('title', 'Créer utilisateur')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        @include('users.form', ['user' => null])
        <button class="btn" type="submit">Créer</button>
    </form>
</div>
@endsection
