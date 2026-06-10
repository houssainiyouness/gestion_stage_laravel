<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'address', 'email', 'phone', 'description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }
}
