<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soutenance extends Model
{
    protected $fillable = [
        'internship_id',
        'date_soutenance',
        'jury',
        'final_note',
        'resultat',
    ];

    protected $casts = [
        'date_soutenance' => 'datetime',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
