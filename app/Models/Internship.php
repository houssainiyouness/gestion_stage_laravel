<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    protected $fillable = [
        'application_id',
        'student_id',
        'organization_id',
        'encadrant_id',
        'subject',
        'service',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function encadrant()
    {
        return $this->belongsTo(User::class, 'encadrant_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function stageForm()
{
    return $this->hasOne(StageForm::class);
}
    public function suivis()
    {
        return $this->hasMany(Suivi::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function soutenance()
    {
        return $this->hasOne(Soutenance::class);
    }
}