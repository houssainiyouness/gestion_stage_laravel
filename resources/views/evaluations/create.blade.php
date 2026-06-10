@extends('layouts.app')

@section('title', 'Ajouter évaluation')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('evaluations.store') }}">
        @csrf

        <div class="form-group">
            <label>Stage</label>
            <select name="internship_id" required>
                <option value="">-- Choisir --</option>
                @foreach($internships as $internship)
                    <option value="{{ $internship->id }}">
                        {{ $internship->subject }} — {{ $internship->student->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Note /20</label>
            <input type="number" step="0.01" min="0" max="20" name="note" value="{{ old('note') }}">
        </div>

        <div class="form-group">
            <label>Commentaire</label>
            <textarea name="commentaire">{{ old('commentaire') }}</textarea>
        </div>

        <button class="btn" type="submit">Ajouter évaluation</button>
    </form>
</div>
@endsection
