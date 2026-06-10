<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mettre à jour toutes les offres dont la date de fin est dépassée
        Offer::where('status', '!=', 'closed')
            ->where('end_date', '<', now())
            ->update(['status' => 'closed']);

        $query = Offer::with('organization', 'creator')->latest();

        if ($user->role === 'admin_organisme') {
            $query->where('organization_id', $user->organization_id);
        }

        if ($user->role === 'etudiant') {
            $query->where('status', 'active');
        }

        $offers = $query->orderByDesc('id')->get();

        return view('offers.index', compact('offers'));
    }

    public function create()
    {
        $organizations = Organization::orderBy('name')->get();
        $profiles = Offer::profiles();
        return view('offers.create', compact('organizations', 'profiles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'required_skills' => 'nullable|string',
            'profile_required' => ['required', 'string', Rule::in(Offer::profiles())],
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,closed',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        // Admin organisme : organisation fixée
        if ($user->role === 'admin_organisme') {
            if (!$user->organization_id) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Votre compte admin n’est associé à aucun organisme.');
            }
            $data['organization_id'] = $user->organization_id;
        }

        // Super admin : doit choisir un organisme
        if ($user->role === 'super_admin' && empty($data['organization_id'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Vous devez choisir un organisme pour l’offre.');
        }

        $data['created_by'] = $user->id;

        Offer::create($data);

        return redirect()->route('offers.index')->with('success', 'Offre ajoutée avec succès.');
    }

    public function show(Offer $offer)
    {
        $offer->load('organization', 'applications.student');
        return view('offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $organizations = Organization::orderBy('name')->get();
        $profiles = Offer::profiles();
        return view('offers.edit', compact('offer', 'organizations', 'profiles'));
    }

    public function update(Request $request, Offer $offer)
    {
        $user = auth()->user();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'profile_required' => ['required', 'string', Rule::in(Offer::profiles())],
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,closed',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        if ($user->role === 'admin_organisme') {
            $data['organization_id'] = $user->organization_id;
        }

        if ($user->role === 'super_admin' && empty($data['organization_id'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Vous devez choisir un organisme pour l’offre.');
        }

        $offer->update($data);
        $this->logAction('update', 'offers', $offer->id, 'Modification offre');

        return redirect()->route('offers.index')->with('success', 'Offre modifiée avec succès.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('offers.index')->with('success', 'Offre supprimée.');
    }
}