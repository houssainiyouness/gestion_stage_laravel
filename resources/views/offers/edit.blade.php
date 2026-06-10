@extends('layouts.app')

@section('title', 'Modifier une offre')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('offers.update', $offer) }}">
        @csrf
        @method('PUT')
        @include('offers.form', ['offer' => $offer])
        <button class="btn" type="submit">Enregistrer</button>
    </form>
</div>
@endsection
