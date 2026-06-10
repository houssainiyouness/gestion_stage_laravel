<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suivi extends Model
{
    protected $fillable = [
        'internship_id',
        'encadrant_id',
        'description',
        'progress_percentage',
        'follow_up_date',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function encadrant()
    {
        return $this->belongsTo(User::class, 'encadrant_id');
    }
}
