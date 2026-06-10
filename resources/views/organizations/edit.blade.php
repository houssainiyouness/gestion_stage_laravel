@extends('layouts.app')

@section('title', 'Modifier organisme')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('organizations.update', $organization) }}">
        @csrf @method('PUT')
        @include('organizations.form', ['organization' => $organization])
        <button class="btn" type="submit">Enregistrer</button>
    </form>
</div>
@endsection
