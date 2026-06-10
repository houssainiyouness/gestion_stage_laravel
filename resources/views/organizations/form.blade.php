<div class="form-group">
    <label>Nom</label>
    <input name="name" value="{{ old('name', $organization->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Adresse</label>
    <input name="address" value="{{ old('address', $organization->address ?? '') }}" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $organization->email ?? '') }}" required>
</div>

<div class="form-group">
    <label>Téléphone</label>
    <input name="phone" value="{{ old('phone', $organization->phone ?? '') }}">
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description">{{ old('description', $organization->description ?? '') }}</textarea>
</div>
