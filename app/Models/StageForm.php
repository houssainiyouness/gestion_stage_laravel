<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StageForm extends Model
{
    protected $fillable = [
        'internship_id',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'cin',
        'email',
        'adresse',
        'telephone_fixe',
        'telephone_portable',

        'etablissement_nom',
        'etablissement_adresse',
        'etablissement_telephone',
        'etablissement_fax',

        'specialite',
        'niveau_etude',
        'filiere',

        'stage_type',
        'jours_stage',
        'horaire_debut',
        'horaire_fin',
        'laboratoire_accueil',
        'maitre_stage',
        'service_affectation',
        'encadrants',
        'sujet_stage',

        'assurance_ok',
        'convention_ok',
        'engagement_ok',
        'submitted_at',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'assurance_ok' => 'boolean',
        'convention_ok' => 'boolean',
        'engagement_ok' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}