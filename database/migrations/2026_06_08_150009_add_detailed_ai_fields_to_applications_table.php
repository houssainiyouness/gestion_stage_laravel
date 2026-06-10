<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('candidate_name')->nullable()->after('message');
            $table->string('candidate_email')->nullable()->after('candidate_name');
            $table->text('candidate_experience')->nullable()->after('candidate_email');
            $table->text('candidate_diplomas')->nullable()->after('candidate_experience');
            $table->unsignedTinyInteger('ai_match_rate')->nullable()->after('ai_score');
            $table->longText('ai_recommendations')->nullable()->after('ai_recommendation');
            $table->timestamp('ai_analyzed_at')->nullable()->after('ai_recommendations');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'candidate_name',
                'candidate_email',
                'candidate_experience',
                'candidate_diplomas',
                'ai_match_rate',
                'ai_recommendations',
                'ai_analyzed_at',
            ]);
        });
    }
};