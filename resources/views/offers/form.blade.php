@if(auth()->user()->role === 'super_admin')
<div class="form-group">
    <label>Organisme</label>
    <select name="organization_id" required>
        <option value="">-- Choisir --</option>
        @foreach($organizations as $organization)
            <option value="{{ $organization->id }}" @selected(old('organization_id', $offer->organization_id ?? '') == $organization->id)>
                {{ $organization->name }}
            </option>
        @endforeach
    </select>
</div>
@endif

<div class="form-group">
    <label>Titre</label>
    <input name="title" value="{{ old('title', $offer->title ?? '') }}" required>
</div>
<div class="form-group">
    <label>Type</label>
    <select name="type" required>
        <option value="">-- Choisir le type --</option>
        @foreach(['Stage de recherche', 'Stage professionnel'] as $type)
            <option value="{{ $type }}" @selected(old('type', $offer->type ?? '') === $type)>
                {{ $type }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Description</label>
    <textarea name="description" required>{{ old('description', $offer->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Compétences demandées</label>
    <textarea 
        name="required_skills" 
        placeholder="Exemple : PHP, Laravel, MySQL, HTML, CSS, Bac +2, Bac +3..."
    >{{ old('required_skills', $offer->required_skills ?? '') }}</textarea>

    <p class="muted">
        Ces compétences seront utilisées par l’IA pour comparer le CV avec l’offre.
    </p>
</div>
<div class="form-group">
    <label>Profil demandé</label>
    <select name="profile_required" required>
        <option value="">-- Choisir le profil demandé --</option>
        @foreach($profiles as $profile)
            <option value="{{ $profile }}" @selected(old('profile_required', $offer->profile_required ?? '') === $profile)>
                {{ $profile }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Lieu</label>
    <input name="location" value="{{ old('location', $offer->location ?? '') }}" required>
</div>

<div class="form-group">
    <label>Date début</label>
    <input type="date" name="start_date" required value="{{ old('start_date', isset($offer) && $offer->start_date ? $offer->start_date->format('Y-m-d') : '') }}">
</div>

<div class="form-group">
    <label>Date fin</label>
    <input type="date" name="end_date" required value="{{ old('end_date', isset($offer) && $offer->end_date ? $offer->end_date->format('Y-m-d') : '') }}">
</div>

<div class="form-group">
    <label>Statut</label>
    <select name="status">
        @foreach(['draft' => 'Brouillon', 'active' => 'Active', 'closed' => 'Fermée'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $offer->status ?? 'active') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
