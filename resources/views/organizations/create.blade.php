@extends('layouts.app')

@section('title', 'Créer organisme')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('organizations.store') }}">
        @csrf
        @include('organizations.form', ['organization' => null])
        <button class="btn" type="submit">Créer</button>
    </form>
</div>
@endsection
