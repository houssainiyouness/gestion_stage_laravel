@extends('layouts.app')

@section('title', 'Ajouter suivi')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('suivis.store') }}">
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
            <label>Description du suivi</label>
            <textarea name="description" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Avancement (%)</label>
            <input type="number" name="progress_percentage" min="0" max="100" value="{{ old('progress_percentage', 0) }}" required>
        </div>

        <div class="form-group">
            <label>Date du suivi</label>
            <input type="date" name="follow_up_date" value="{{ old('follow_up_date', date('Y-m-d')) }}" required>
        </div>

        <button class="btn" type="submit">Ajouter suivi</button>
    </form>
</div>
@endsection
