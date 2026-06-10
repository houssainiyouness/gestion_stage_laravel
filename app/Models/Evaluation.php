<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'internship_id',
        'encadrant_id',
        'note',
        'commentaire',
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
