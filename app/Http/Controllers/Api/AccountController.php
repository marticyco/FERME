<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Afficher tous les comptes
    public function index()
    {
        $accounts = Account::with('children')->get();
        return response()->json($accounts);
    }

    // Afficher un compte spécifique
    public function show($id)
    {
        $account = Account::with('children', 'journalLines')->find($id);

        if (!$account) {
            return response()->json(['message' => 'Compte non trouvé'], 404);
        }

        return response()->json($account);
    }

    // Créer un compte
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        $account = Account::create([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json($account, 201);
    }

    // Mettre à jour un compte
    public function update(Request $request, $id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'Compte non trouvé'], 404);
        }

        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        $account->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json($account);
    }

    // Supprimer un compte
    public function destroy($id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'Compte non trouvé'], 404);
        }

        $account->delete();
        return response()->json(['message' => 'Compte supprimé avec succès']);
    }
}
