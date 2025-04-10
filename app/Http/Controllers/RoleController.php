<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }
        
        // Modifier pour utiliser paginate() au lieu de get()
        $roles = Role::withCount('users')->orderBy('nom')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }
        
        // Récupérer toutes les permissions disponibles
        $permissions = Permission::orderBy('nom')->get();
        
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:roles',
            'description' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            // Correction des champs booléens pour accepter l'absence comme 'false'
            'can_manage_books' => 'boolean',
            'can_manage_categories' => 'boolean',
            'can_manage_comments' => 'boolean',
            'can_manage_sales' => 'boolean',
            'can_view_dashboard' => 'boolean',
            'max_books_per_day' => 'nullable|integer|min:0',
            'max_comments_per_day' => 'nullable|integer|min:0',
        ]);

        try {
            // Utiliser les valeurs par défaut pour les champs booléens manquants
            $role = Role::create([
                'nom' => $validated['nom'],
                'description' => $validated['description'],
                'can_manage_books' => $request->has('can_manage_books') ? (bool)$request->can_manage_books : false,
                'can_manage_categories' => $request->has('can_manage_categories') ? (bool)$request->can_manage_categories : false,
                'can_manage_comments' => $request->has('can_manage_comments') ? (bool)$request->can_manage_comments : false,
                'can_manage_sales' => $request->has('can_manage_sales') ? (bool)$request->can_manage_sales : false,
                'can_view_dashboard' => $request->has('can_view_dashboard') ? (bool)$request->can_view_dashboard : false,
                'max_books_per_day' => $validated['max_books_per_day'] ?? null,
                'max_comments_per_day' => $validated['max_comments_per_day'] ?? null,
            ]);

            // Attacher les permissions si elles existent
            if (isset($validated['permissions'])) {
                $role->permissions()->attach($validated['permissions']);
            }

            return redirect()->route('roles.index')
                ->with('success', "Rôle '{$role->nom}' créé avec succès.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Erreur lors de la création du rôle: {$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }
        
        $role->load('permissions', 'users');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }
        
        // Pour les rôles système, rediriger vers la page d'assignation d'utilisateurs
        if (in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client'])) {
            return redirect()->route('roles.assign-users', $role)
                ->with('info', 'Les propriétés des rôles système ne peuvent pas être modifiées, mais vous pouvez gérer les utilisateurs qui y sont assignés.');
        }
        
        $permissions = Permission::orderBy('nom')->get();
        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }
        
        // Ne pas permettre la modification des rôles système
        if (in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client'])) {
            return redirect()->route('roles.index')
                ->with('error', 'Les rôles système ne peuvent pas être modifiés.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:roles,nom,' . $role->id,
            'description' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            // Correction des champs booléens pour accepter l'absence comme 'false'
            'can_manage_books' => 'boolean',
            'can_manage_categories' => 'boolean',
            'can_manage_comments' => 'boolean',
            'can_manage_sales' => 'boolean',
            'can_view_dashboard' => 'boolean',
            'max_books_per_day' => 'nullable|integer|min:0',
            'max_comments_per_day' => 'nullable|integer|min:0',
        ]);

        $role->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'can_manage_books' => $request->has('can_manage_books') ? (bool)$request->can_manage_books : false,
            'can_manage_categories' => $request->has('can_manage_categories') ? (bool)$request->can_manage_categories : false,
            'can_manage_comments' => $request->has('can_manage_comments') ? (bool)$request->can_manage_comments : false,
            'can_manage_sales' => $request->has('can_manage_sales') ? (bool)$request->can_manage_sales : false,
            'can_view_dashboard' => $request->has('can_view_dashboard') ? (bool)$request->can_view_dashboard : false,
            'max_books_per_day' => $validated['max_books_per_day'] ?? null,
            'max_comments_per_day' => $validated['max_comments_per_day'] ?? null,
        ]);

        // Synchroniser les permissions
        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }
        
        // Ne pas permettre la suppression des rôles système
        if (in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client'])) {
            return redirect()->route('roles.index')
                ->with('error', 'Les rôles système ne peuvent pas être supprimés.');
        }

        // Vérifier s'il y a des utilisateurs associés au rôle
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Ce rôle est assigné à des utilisateurs et ne peut pas être supprimé.');
        }

        // Supprimer les relations avec les permissions
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }

    /**
     * Show the form for assigning users to a role.
     */
    public function assignUsers(Role $role)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }

        // Récupérer tous les utilisateurs
        $users = User::orderBy('name')->get();
        
        // Récupérer les IDs des utilisateurs déjà assignés à ce rôle
        $roleUsers = $role->users()->pluck('users.id')->toArray();
        
        // Variable pour indiquer s'il s'agit d'un rôle système
        $isSystemRole = in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client']);
        
        return view('roles.assign-users', compact('role', 'users', 'roleUsers', 'isSystemRole'));
    }

    /**
     * Update user assignments for a role.
     */
    public function updateUsers(Request $request, Role $role)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les rôles.");
        }

        // Déterminer l'action à effectuer (ajouter ou supprimer)
        $action = $request->input('action', 'add');

        if ($action === 'add') {
            // Validation pour l'ajout d'utilisateurs
            $validated = $request->validate([
                'users_to_add' => 'required|array',
                'users_to_add.*' => 'exists:users,id',
            ]);

            // Ajouter les nouveaux utilisateurs sans supprimer les existants
            $role->users()->syncWithoutDetaching($validated['users_to_add']);
            
            $message = 'Nouveaux utilisateurs assignés avec succès au rôle.';
        } else {
            // Validation pour la suppression d'utilisateurs
            $validated = $request->validate([
                'users_to_remove' => 'required|array',
                'users_to_remove.*' => 'exists:users,id',
            ]);

            // Détacher uniquement les utilisateurs sélectionnés
            $role->users()->detach($validated['users_to_remove']);
            
            $message = 'Utilisateurs sélectionnés retirés du rôle avec succès.';
        }

        return redirect()->route('roles.show', $role)
            ->with('success', $message);
    }
}
