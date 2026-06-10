<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
   public function index()
{
    $users = User::with('organization')
        ->where('role', '!=', 'super_admin')
        ->latest()
        ->paginate(20);

    return view('users.index', compact('users'));
}

    public function create()
    {
        $organizations = Organization::orderBy('name')->get();
        return view('users.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'role' => ['required', Rule::in(['admin_organisme', 'encadrant', 'etudiant'])],
            'organization_id' => 'nullable|exists:organizations,id',
            'password' => 'required|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $this->logAction('create', 'users', $user->id, 'Création utilisateur');

        return redirect()->route('users.index')->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user)
    {
        $organizations = Organization::orderBy('name')->get();
        return view('users.edit', compact('user', 'organizations'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
            'role' => ['required', Rule::in(['super_admin', 'admin_organisme', 'encadrant', 'etudiant'])],
            'organization_id' => 'nullable|exists:organizations,id',
            'password' => 'nullable|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        $this->logAction('update', 'users', $user->id, 'Modification utilisateur');

        return redirect()->route('users.index')->with('success', 'Utilisateur modifié.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tu ne peux pas supprimer ton propre compte.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
