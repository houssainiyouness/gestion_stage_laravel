@extends('layouts.app')

@section('title', 'Créer une offre')

@section('content')

<div class="card">
 
    <form method="POST" action="{{ route('offers.store') }}">
        @csrf
        @include('offers.form', ['offer' => null])
        <button class="btn" type="submit">Créer</button>
    </form>


</div>
@endsection
