<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stage_forms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('internship_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('cin')->nullable();
            $table->string('email')->nullable();
            $table->text('adresse')->nullable();
            $table->string('telephone_fixe')->nullable();
            $table->string('telephone_portable')->nullable();

            $table->string('etablissement_nom')->nullable();
            $table->text('etablissement_adresse')->nullable();
            $table->string('etablissement_telephone')->nullable();
            $table->string('etablissement_fax')->nullable();

            $table->string('specialite')->nullable();
            $table->string('niveau_etude')->nullable();
            $table->string('filiere')->nullable();

            $table->enum('stage_type', ['nouveau', 'renouvellement'])->default('nouveau');
            $table->string('jours_stage')->nullable();
            $table->string('horaire_debut')->nullable();
            $table->string('horaire_fin')->nullable();

            $table->string('laboratoire_accueil')->nullable();
            $table->string('maitre_stage')->nullable();
            $table->string('service_affectation')->nullable();
            $table->string('encadrants')->nullable();
            $table->text('sujet_stage')->nullable();

            $table->boolean('assurance_ok')->default(false);
            $table->boolean('convention_ok')->default(false);
            $table->boolean('engagement_ok')->default(false);
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stage_forms');
    }
};