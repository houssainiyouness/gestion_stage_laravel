@extends('layouts.app')

@section('title', 'Postuler à une offre')

@section('content')
<div class="card">
    <h3>{{ $offer->title }}</h3>
    <p class="muted">{{ $offer->organization->name ?? '-' }}</p>

    <form method="POST" action="{{ route('applications.store', $offer) }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>CV</label>
           <input type="file" name="cv_path" required accept=".pdf,.doc,.docx"> 
       </div>

        <div class="form-group">
            <label>Lettre de motivation</label>
            <input type="file" name="motivation_letter_path" required accept=".pdf,.doc,.docx">
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea name="message">{{ old('message') }}</textarea>
        </div>

        <button class="btn green" type="submit">Envoyer candidature</button>
    </form>
</div>
@endsection
