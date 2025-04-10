<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Changé de 'nom' à 'name'
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'], // Changé de 'nom' à 'name'
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->attach($validated['roles']);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles', 'comments');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        // Correction pour éviter l'ambiguïté de colonne
        $userRoles = $user->roles()->pluck('roles.id')->toArray();
        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Changé de 'nom' à 'name'
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->getKey(),
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $userData = [
            'name' => $validated['name'], // Changé de 'nom' à 'name'
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->roles()->sync($validated['roles']);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Show form to edit a user's profile as an admin
     */
    public function editProfile(User $user)
    {
        // Vérifier que l'utilisateur connecté est un admin
        // Correction: Vérifier si l'utilisateur a le rôle 'admin' en utilisant les relations
        $currentUser = Auth::user();
        $isAdmin = DB::table('users')
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('users.id', $currentUser->id)
            ->where('roles.nom', 'admin')
            ->exists();
            
        if (!$isAdmin) {
            abort(403);
        }
        
        return view('users.edit-profile', compact('user'));
    }

    /**
     * Update a user's profile as an admin
     */
    public function updateProfile(Request $request, User $user)
    {
        // Vérifier que l'utilisateur connecté est un admin
        $currentUser = Auth::user();
        $isAdmin = DB::table('users')
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('users.id', $currentUser->id)
            ->where('roles.nom', 'admin')
            ->exists();
            
        if (!$isAdmin) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        $user->fill($validated);
        $user->save();
        
        return redirect()->route('users.show', $user)->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Show form to restrict a user
     */
    public function showRestrict(User $user)
    {
        // Vérifier que l'utilisateur connecté est un admin
        $currentUser = Auth::user();
        $isAdmin = DB::table('users')
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('users.id', $currentUser->id)
            ->where('roles.nom', 'admin')
            ->exists();
            
        if (!$isAdmin) {
            abort(403);
        }
        
        return view('users.restrict', compact('user'));
    }

    /**
     * Restrict a user's account
     */
    public function restrict(Request $request, User $user)
    {
        // Vérifier que l'utilisateur connecté est un admin
        $currentUser = Auth::user();
        $isAdmin = DB::table('users')
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('users.id', $currentUser->id)
            ->where('roles.nom', 'admin')
            ->exists();
            
        if (!$isAdmin) {
            abort(403);
        }
        
        $validated = $request->validate([
            'restriction_reason' => 'required|string|max:255',
        ]);
        
        $user->fill([
            'is_restricted' => true,
            'restriction_reason' => $validated['restriction_reason'],
            'restricted_at' => now(),
        ]);
        $user->save();
        
        // Créer une alerte pour l'utilisateur
        Alert::create([
            'user_id' => $user->id,
            'created_by' => Auth::id(),
            'message' => 'Votre compte a été restreint: ' . $validated['restriction_reason'],
            'type' => 'danger',
        ]);
        
        return redirect()->route('users.show', $user)->with('success', 'Compte restreint avec succès');
    }

    /**
     * Remove restriction from a user's account
     */
    public function unrestrict(User $user)
    {
        // Vérifier que l'utilisateur connecté est un admin
        $currentUser = Auth::user();
        $isAdmin = DB::table('users')
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('users.id', $currentUser->id)
            ->where('roles.nom', 'admin')
            ->exists();
            
        if (!$isAdmin) {
            abort(403);
        }
        
        $user->fill([
            'is_restricted' => false,
            'restriction_reason' => null,
            'restricted_at' => null,
        ]);
        $user->save();
        
        // Créer une alerte pour l'utilisateur
        Alert::create([
            'user_id' => $user->id,
            'created_by' => Auth::id(),
            'message' => 'La restriction sur votre compte a été levée.',
            'type' => 'info',
        ]);
        
        return redirect()->route('users.show', $user)->with('success', 'Restriction du compte levée avec succès');
    }
}
