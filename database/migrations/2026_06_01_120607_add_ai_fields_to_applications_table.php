<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->text('candidate_skills')->nullable()->after('message');
            $table->unsignedTinyInteger('ai_score')->nullable()->after('candidate_skills');
            $table->text('ai_keywords_found')->nullable()->after('ai_score');
            $table->text('ai_keywords_missing')->nullable()->after('ai_keywords_found');
            $table->text('ai_summary')->nullable()->after('ai_keywords_missing');
            $table->string('ai_recommendation')->nullable()->after('ai_summary');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'candidate_skills',
                'ai_score',
                'ai_keywords_found',
                'ai_keywords_missing',
                'ai_summary',
                'ai_recommendation',
            ]);
        });
    }
};