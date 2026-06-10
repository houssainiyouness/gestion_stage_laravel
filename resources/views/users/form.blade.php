<div class="form-group">
    <label>Nom</label>
    <input name="name" value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="form-group">
    <label>Téléphone</label>
    <input name="phone" value="{{ old('phone', $user->phone ?? '') }}">
</div>

<div class="form-group">
    <label>Rôle</label>
    <select name="role" required>
        @foreach(['admin_organisme','encadrant','etudiant'] as $role)
            <option value="{{ $role }}" @selected(old('role', $user->role ?? 'etudiant') === $role)>{{ $role }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Organisme</label>
    <select name="organization_id">
        <option value="">-- Aucun --</option>
        @foreach($organizations as $organization)
            <option value="{{ $organization->id }}" @selected(old('organization_id', $user->organization_id ?? '') == $organization->id)>
                {{ $organization->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Mot de passe {{ isset($user) ? '(laisser vide pour ne pas changer)' : '' }}</label>
    <input type="password" name="password" {{ isset($user) ? '' : 'required' }}>
</div>
