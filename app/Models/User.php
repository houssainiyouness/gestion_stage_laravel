<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_id',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'student_id');
    }

    public function internshipsAsStudent()
    {
        return $this->hasMany(Internship::class, 'student_id');
    }

    public function internshipsAsEncadrant()
    {
        return $this->hasMany(Internship::class, 'encadrant_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminOrganisme(): bool
    {
        return $this->role === 'admin_organisme';
    }

    public function isEncadrant(): bool
    {
        return $this->role === 'encadrant';
    }

    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }
}
