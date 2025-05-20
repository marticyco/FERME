<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Liste tous les utilisateurs avec leurs rôles et permissions.
     */
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        return response()->json($users, 200);
    }

    /**
     * Afficher un utilisateur spécifique avec ses rôles et permissions.
     */
    public function show($userId)
    {
        $user = User::with('roles', 'permissions')->findOrFail($userId);
        return response()->json($user, 200);
    }

    /**
     * Modifier un utilisateur (nom, email, rôles et permissions).
     */
    public function update(Request $request, $id)
{
    // Vérifie que l'ID est un UUID valide
    if (!is_string($id) || !preg_match('/^[a-f0-9\-]{36}$/', $id)) {
        return response()->json(['error' => 'ID utilisateur invalide'], 400);
    }

    $user = User::where('id', $id)->first();

    if (!$user) {
        return response()->json(['error' => 'Utilisateur non trouvé'], 404);
    }

    // Mise à jour des informations utilisateur
    $user->update($request->only(['name', 'email']));

    // Mise à jour des rôles
    if ($request->filled('roles')) {
        $user->syncRoles($request->roles);
    } else {
        $user->roles()->detach();
    }

    // Mise à jour des permissions
    if ($request->filled('permissions')) {
        $user->syncPermissions($request->permissions);
    } else {
        $user->permissions()->detach();
    }

    return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user], 200);
}

    
    /**
     * Assigner un rôle à un utilisateur.
     */
    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->assignRole($request->role);

        return response()->json(['message' => 'Rôle assigné avec succès'], 200);
    }

    /**
     * Retirer un rôle d'un utilisateur.
     */
    public function removeRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->removeRole($request->role);

        return response()->json(['message' => 'Rôle retiré avec succès'], 200);
    }

    /**
     * Assigner une permission à un utilisateur.
     */
    public function givePermission(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'permission' => 'required|string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->givePermissionTo($request->permission);

        return response()->json(['message' => 'Permission assignée avec succès'], 200);
    }

    /**
     * Retirer une permission d'un utilisateur.
     */
    public function revokePermission(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'permission' => 'required|string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->revokePermissionTo($request->permission);

        return response()->json(['message' => 'Permission retirée avec succès'], 200);
    }


  /**
 * Créer un nouvel utilisateur.
 */
public function store(Request $request)
{
    try {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'sometimes|array',
            'permissions' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // ou Hash::make(...)
        ]);

        // Assignation des rôles si fournis
        if ($request->filled('roles')) {
            try {
                $user->assignRole($request->roles);
            } catch (\Exception $e) {
                \Log::warning("Échec d'assignation des rôles", [
                    'user_id' => $user->id,
                    'roles' => $request->roles,
                    'exception' => $e->getMessage()
                ]);
            }
        }

        // Assignation des permissions si fournies
        if ($request->filled('permissions')) {
            try {
                $user->givePermissionTo($request->permissions);
            } catch (\Exception $e) {
                \Log::warning("Échec d'assignation des permissions", [
                    'user_id' => $user->id,
                    'permissions' => $request->permissions,
                    'exception' => $e->getMessage()
                ]);
            }
        }

        return response()->json(['message' => 'Utilisateur créé avec succès', 'user' => $user], 201);
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la création utilisateur', [
            'exception' => $e->getMessage(),
            'data' => $request->all()
        ]);

        return response()->json([
            'error' => 'Erreur interne du serveur: ' . $e->getMessage()
        ], 500);
    }
}


/**
 * Supprimer un utilisateur.
 */
public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'Utilisateur non trouvé'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'Utilisateur supprimé avec succès.'], 200);
}


}
