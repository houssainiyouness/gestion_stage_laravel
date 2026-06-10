<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'internship_id',
        'application_id',
        'student_id',
        'type',
        'file_path',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
