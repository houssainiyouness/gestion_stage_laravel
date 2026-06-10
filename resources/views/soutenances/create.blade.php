@extends('layouts.app')

@section('title', 'Planifier soutenance')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('soutenances.store') }}">
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
            <label>Date soutenance</label>
            <input type="datetime-local" name="date_soutenance" required>
        </div>

        <div class="form-group">
            <label>Jury</label>
            <textarea name="jury" required>{{ old('jury') }}</textarea>
        </div>

        <div class="form-group">
            <label>Note finale</label>
            <input type="number" step="0.01" min="0" max="20" name="final_note">
        </div>

        <div class="form-group">
            <label>Résultat</label>
            <select name="resultat">
                <option value="en_attente">En attente</option>
                <option value="valide">Validé</option>
                <option value="non_valide">Non validé</option>
            </select>
        </div>

        <button class="btn" type="submit">Enregistrer</button>
    </form>
</div>
@endsection
