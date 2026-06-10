@extends('layouts.app')

@section('title', 'Formulaires administratifs')

@section('content')

<div class="card">
    <h3>Formulaires à remplir</h3>

    <p class="muted">
        Remplissez ces informations directement dans la plateforme.
        Après enregistrement, l’administration pourra imprimer les formulaires et les faire signer.
    </p>

    <p><strong>Stage :</strong> {{ $internship->subject }}</p>

    <p>
        <strong>Période :</strong>
        {{ $internship->start_date ? $internship->start_date->format('d/m/Y') : '-' }}
        au
        {{ $internship->end_date ? $internship->end_date->format('d/m/Y') : '-' }}
    </p>
</div>

<form method="POST" action="{{ route('internships.forms.update', $internship) }}">
    @csrf
    @method('PUT')

    <div class="card">
        <h3>1. Identité du stagiaire</h3>

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required value="{{ old('nom', $stageForm->nom) }}">
            </div>

            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" required value="{{ old('prenom', $stageForm->prenom) }}">
            </div>

            <div class="form-group">
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" required value="{{ old('date_naissance', optional($stageForm->date_naissance)->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label>Lieu de naissance</label>
                <input type="text" name="lieu_naissance" required value="{{ old('lieu_naissance', $stageForm->lieu_naissance) }}">
            </div>

            <div class="form-group">
                <label>Nationalité</label>
                <input type="text" name="nationalite" required value="{{ old('nationalite', $stageForm->nationalite) }}">
            </div>

            <div class="form-group">
                <label>CIN / Passeport</label>
                <input type="text" name="cin" value="{{ old('cin', $stageForm->cin) }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="{{ old('email', $stageForm->email) }}">
            </div>

            <div class="form-group">
                <label>Téléphone portable</label>
                <input type="text" name="telephone_portable" required value="{{ old('telephone_portable', $stageForm->telephone_portable) }}">
            </div>

            <div class="form-group">
                <label>Téléphone fixe</label>
                <input type="text" name="telephone_fixe" value="{{ old('telephone_fixe', $stageForm->telephone_fixe) }}">
            </div>

            <div class="form-group" style="grid-column:1 / -1;">
                <label>Adresse</label>
                <textarea name="adresse">{{ old('adresse', $stageForm->adresse) }}</textarea>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>2. Établissement d’origine et formation</h3>

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
            <div class="form-group">
                <label>Nom de l’établissement</label>
                <input type="text" name="etablissement_nom" required value="{{ old('etablissement_nom', $stageForm->etablissement_nom) }}">
            </div>

            <div class="form-group">
                <label>Téléphone établissement</label>
                <input type="text" name="etablissement_telephone" value="{{ old('etablissement_telephone', $stageForm->etablissement_telephone) }}">
            </div>

            <div class="form-group">
                <label>Fax établissement</label>
                <input type="text" name="etablissement_fax" value="{{ old('etablissement_fax', $stageForm->etablissement_fax) }}">
            </div>

            <div class="form-group">
                <label>Niveau d’étude</label>
                <input type="text" name="niveau_etude" required value="{{ old('niveau_etude', $stageForm->niveau_etude) }}" placeholder="Exemple : Bac+2, Bac+3, Master">
            </div>

            <div class="form-group">
                <label>Spécialité</label>
                <input type="text" name="specialite" required value="{{ old('specialite', $stageForm->specialite) }}">
            </div>

            <div class="form-group">
                <label>Filière</label>
                <input type="text" name="filiere" required value="{{ old('filiere', $stageForm->filiere) }}">
            </div>

            <div class="form-group" style="grid-column:1 / -1;">
                <label>Adresse établissement</label>
                <textarea name="etablissement_adresse">{{ old('etablissement_adresse', $stageForm->etablissement_adresse) }}</textarea>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>3. Informations du stage</h3>

        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
            <div class="form-group" required>
                <label>Type de stage</label>
                <select name="stage_type" required>
                    <option value="nouveau" @selected(old('stage_type', $stageForm->stage_type) === 'nouveau')>Nouveau stage</option>
                    <option value="renouvellement" @selected(old('stage_type', $stageForm->stage_type) === 'renouvellement')>Renouvellement de stage</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jours de stage</label>
                <input type="text" name="jours_stage" value="{{ old('jours_stage', $stageForm->jours_stage) }}" placeholder="Exemple : Lundi à Vendredi">
            </div>

            <div class="form-group">
                <label>Horaire début</label>
                <input type="text" name="horaire_debut" value="{{ old('horaire_debut', $stageForm->horaire_debut) }}" placeholder="Exemple : 09h00">
            </div>

            <div class="form-group">
                <label>Horaire fin</label>
                <input type="text" name="horaire_fin" value="{{ old('horaire_fin', $stageForm->horaire_fin) }}" placeholder="Exemple : 16h00">
            </div>

            <div class="form-group">
                <label>Laboratoire d’accueil</label>
                <input type="text" name="laboratoire_accueil" value="{{ old('laboratoire_accueil', $stageForm->laboratoire_accueil) }}">
            </div>

            <div class="form-group">
                <label>Maître de stage</label>
                <input type="text" name="maitre_stage" value="{{ old('maitre_stage', $stageForm->maitre_stage) }}">
            </div>

            <div class="form-group">
                <label>Service d’affectation</label>
                <input type="text" name="service_affectation" value="{{ old('service_affectation', $stageForm->service_affectation) }}">
            </div>

            <div class="form-group">
                <label>Encadrant(s)</label>
                <input type="text" name="encadrants" value="{{ old('encadrants', $stageForm->encadrants) }}">
            </div>

            <div class="form-group" style="grid-column:1 / -1;">
                <label>Sujet de stage</label>
                <textarea name="sujet_stage">{{ old('sujet_stage', $stageForm->sujet_stage) }}</textarea>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>4. Pièces obligatoires et engagement</h3>

        <p class="muted">
            L’étudiant doit avoir une attestation d’assurance et une convention de stage avant le début du stage.
        </p>

        <div class="form-group">
            <label style="font-weight:400;">
                <input type="checkbox" name="assurance_ok" required value="1" style="width:auto;" @checked(old('assurance_ok', $stageForm->assurance_ok))>
                Je confirme que l’attestation d’assurance sera fournie.
            </label>
        </div>

        <div class="form-group">
            <label style="font-weight:400;">
                <input type="checkbox" name="convention_ok" required value="1" style="width:auto;" @checked(old('convention_ok', $stageForm->convention_ok))>
                Je confirme que la convention de stage sera fournie.
            </label>
        </div>

        <div class="form-group">
            <label style="font-weight:400;">
                <input type="checkbox" name="engagement_ok" required value="1" style="width:auto;" @checked(old('engagement_ok', $stageForm->engagement_ok))>
                Je reconnais avoir pris connaissance du règlement de stage et des règles de confidentialité.
            </label>
        </div>
    </div>

    <div class="actions">
        <button class="btn green" type="submit">Enregistrer les formulaires</button>
        <a class="btn secondary" href="{{ route('internships.show', $internship) }}">Retour</a>
    </div>
</form>

@endsection