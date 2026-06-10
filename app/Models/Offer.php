<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public static function profiles(): array
    {
        return [
            'Bac +2',
            'Bac +3',
            'Bac +4',
            'Bac +5',
        ];
    }

protected $fillable = [
    'title',
    'type',
    'description',
    'required_skills',
    'profile_required',
    'location',
    'start_date',
    'end_date',
    'status',
    'organization_id',
    'created_by',
];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
public function getCurrentStatusAttribute()
{
    if ($this->start_date && now()->lt($this->start_date)) {
        return 'à venir';
    }

    if ($this->end_date && now()->gt($this->end_date)) {
        return 'closed';
    }

    return $this->status; // active, draft, etc.
}

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
