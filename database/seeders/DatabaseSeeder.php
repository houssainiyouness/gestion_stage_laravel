<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use App\Models\Offer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $org = Organization::create([
            'name' => 'Organisme Démo',
            'address' => 'Casablanca, Maroc',
            'email' => 'contact@organisme-demo.ma',
            'phone' => '0600000000',
            'description' => 'Organisme de démonstration pour tester la gestion des stages.',
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'super_admin',
        ]);

        $admin = User::create([
            'name' => 'Admin Organisme',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin_organisme',
            'organization_id' => $org->id,
        ]);

        User::create([
            'name' => 'Encadrant Démo',
            'email' => 'encadrant@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'encadrant',
            'organization_id' => $org->id,
        ]);

        User::create([
            'name' => 'Étudiant Démo',
            'email' => 'etudiant@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'etudiant',
        ]);

        Offer::create([
            'organization_id' => $org->id,
            'created_by' => $admin->id,
            'title' => 'Stage Développement Web Laravel',
            'description' => 'Participation au développement d’une plateforme de gestion interne.',
            'profile_required' => 'PHP, Laravel, MySQL, HTML/CSS.',
            'location' => 'Casablanca',
            'start_date' => now()->addMonth()->toDateString(),
            'end_date' => now()->addMonths(4)->toDateString(),
            'status' => 'active',
        ]);
    }
}
