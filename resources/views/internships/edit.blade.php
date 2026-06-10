@extends('layouts.app')

@section('title', 'Modifier stage')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('internships.update', $internship) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Sujet</label>
            <input name="subject" value="{{ old('subject', $internship->subject) }}" required>
        </div>

        <div class="form-group">
            <label>Service</label>
            <input name="service" value="{{ old('service', $internship->service) }}">
        </div>

        <div class="form-group">
            <label>Encadrant</label>
            <select name="encadrant_id">
                <option value="">-- Non affecté --</option>
                @foreach($encadrants as $encadrant)
                    <option value="{{ $encadrant->id }}" @selected(old('encadrant_id', $internship->encadrant_id) == $encadrant->id)>
                        {{ $encadrant->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Date début</label>
            <input type="date" name="start_date" value="{{ old('start_date', $internship->start_date ? $internship->start_date->format('Y-m-d') : '') }}">
        </div>

        <div class="form-group">
            <label>Date fin</label>
            <input type="date" name="end_date" value="{{ old('end_date', $internship->end_date ? $internship->end_date->format('Y-m-d') : '') }}">
        </div>

        <div class="form-group">
            <label>Statut</label>
            <select name="status">
                @foreach(['en_attente','en_cours','termine','annule'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $internship->status) === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn" type="submit">Enregistrer</button>
    </form>
</div>
@endsection
