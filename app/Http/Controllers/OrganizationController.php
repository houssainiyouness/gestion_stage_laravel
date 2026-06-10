<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::latest()->paginate(10);
        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'description' => 'nullable|string',
        ]);

        $organization = Organization::create($data);
        $this->logAction('create', 'organizations', $organization->id, 'Création organisme');

        return redirect()->route('organizations.index')->with('success', 'Organisme créé avec succès.');
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'description' => 'nullable|string',
        ]);

        $organization->update($data);
        $this->logAction('update', 'organizations', $organization->id, 'Modification organisme');

        return redirect()->route('organizations.index')->with('success', 'Organisme modifié avec succès.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organizations.index')->with('success', 'Organisme supprimé.');
    }
}
