<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Internship;
use App\Models\Offer;
use App\Models\Organization;
use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'users' => User::count(),
            'organizations' => Organization::count(),
            'offers' => Offer::count(),
            'applications' => Application::count(),
            'internships' => Internship::count(),
            'soutenances' => Soutenance::count(),
            'acceptance_rate' => 0,
            'success_rate' => 0,
        ];

        $totalApplications = Application::count();
        if ($totalApplications > 0) {
            $stats['acceptance_rate'] = round(Application::where('status', 'acceptee')->count() * 100 / $totalApplications, 2);
        }

        $totalSoutenances = Soutenance::whereNotNull('final_note')->count();
        if ($totalSoutenances > 0) {
            $stats['success_rate'] = round(Soutenance::where('resultat', 'valide')->count() * 100 / $totalSoutenances, 2);
        }

        if ($user->role === 'admin_organisme') {
            $stats['offers'] = Offer::where('organization_id', $user->organization_id)->count();
            $stats['applications'] = Application::whereHas('offer', fn($q) => $q->where('organization_id', $user->organization_id))->count();
            $stats['internships'] = Internship::where('organization_id', $user->organization_id)->count();
        }

        if ($user->role === 'encadrant') {
            $stats['internships'] = Internship::where('encadrant_id', $user->id)->count();
            $stats['soutenances'] = Soutenance::whereHas('internship', fn($q) => $q->where('encadrant_id', $user->id))->count();
        }

        if ($user->role === 'etudiant') {
            $stats['applications'] = Application::where('student_id', $user->id)->count();
            $stats['internships'] = Internship::where('student_id', $user->id)->count();
        }

        return view('dashboard.index', compact('stats', 'user'));
    }
}
