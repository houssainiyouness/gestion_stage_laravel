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
        $org = Organization::firstOrCreate(
            ['email' => 'contact@organisme-demo.ma'],
            [
                'name' => 'Organisme Démo',
                'address' => 'Casablanca, Maroc',
                'phone' => '0600000000',
                'description' => 'Organisme de démonstration pour tester la gestion des stages.',
            ]
        );

        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'role' => 'super_admin',
                'organization_id' => null,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Organisme',
                'password' => Hash::make('12345678'),
                'role' => 'admin_organisme',
                'organization_id' => $org->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'encadrant@example.com'],
            [
                'name' => 'Encadrant Démo',
                'password' => Hash::make('12345678'),
                'role' => 'encadrant',
                'organization_id' => $org->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'etudiant@example.com'],
            [
                'name' => 'Étudiant Démo',
                'password' => Hash::make('12345678'),
                'role' => 'etudiant',
                'organization_id' => null,
            ]
        );

        Offer::firstOrCreate(
            ['title' => 'Stage Développement Web Laravel'],
            [
                'organization_id' => $org->id,
                'created_by' => $admin->id,
                'description' => 'Participation au développement d’une plateforme de gestion interne.',
                'profile_required' => 'PHP, Laravel, MySQL, HTML/CSS.',
                'location' => 'Casablanca',
                'start_date' => now()->addMonth()->toDateString(),
                'end_date' => now()->addMonths(4)->toDateString(),
                'status' => 'active',
            ]
        );
    }
}