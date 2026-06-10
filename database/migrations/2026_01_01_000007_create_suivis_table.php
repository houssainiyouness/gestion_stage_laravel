<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suivis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('encadrant_id')->constrained('users')->cascadeOnDelete();
            $table->text('description');
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->date('follow_up_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivis');
    }
};
