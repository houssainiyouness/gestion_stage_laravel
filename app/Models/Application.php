<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'offer_id',
        'student_id',
        'cv_path',
        'motivation_letter_path',
        'status',
        'message',
        // Infos candidat extraites par IA
        'candidate_name',
        'candidate_email',
        'candidate_experience',
        'candidate_diplomas',
        'candidate_skills',
        // Résultats IA
        'ai_score',
        'ai_match_rate',
        'ai_keywords_found',
        'ai_keywords_missing',
        'ai_summary',
        'ai_recommendation',
        'ai_recommendations',
        'ai_analyzed_at',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function internship()
    {
        return $this->hasOne(Internship::class);
    }
}