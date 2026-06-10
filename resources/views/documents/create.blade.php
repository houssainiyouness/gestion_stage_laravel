@extends('layouts.app')

@section('title', 'Ajouter document')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
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
            <label>Type</label>
            <select name="type" required>
                <option value="convention">Convention</option>
                <option value="rapport">Rapport</option>
                <option value="autre">Autre</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fichier</label>
            <input type="file" name="file" required>
        </div>

        <button class="btn" type="submit">Ajouter</button>
    </form>
</div>
@endsection
