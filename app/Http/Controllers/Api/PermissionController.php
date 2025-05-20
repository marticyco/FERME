<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    /**
     * Liste toutes les permissions.
     */
    public function index()
    {
        return response()->json(Permission::all(), 200);
    }

    /**
     * Crée une nouvelle permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json(['message' => 'Permission créée avec succès', 'permission' => $permission], 201);
    }

    /**
     * Affiche une permission spécifique.
     */
    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission non trouvée'], 404);
        }

        return response()->json($permission, 200);
    }

    /**
     * Met à jour une permission.
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission non trouvée'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return response()->json(['message' => 'Permission mise à jour avec succès', 'permission' => $permission], 200);
    }

    /**
     * Supprime une permission.
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission non trouvée'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission supprimée avec succès'], 200);
    }
}
