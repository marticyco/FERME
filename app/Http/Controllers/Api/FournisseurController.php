<?php

// app/Http/Controllers/API/FournisseurController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    // Afficher la liste des fournisseurs
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        return response()->json($fournisseurs);
    }

    // Afficher un fournisseur spécifique
    public function show($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return response()->json($fournisseur);
    }

    // Créer un nouveau fournisseur
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'contact' => 'required|string',
        ]);

        $fournisseur = Fournisseur::create($validated);
        return response()->json($fournisseur, 201);
    }

    // Mettre à jour un fournisseur existant
    public function update(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->update($request->all());
        return response()->json($fournisseur);
    }

    // Supprimer un fournisseur
    public function destroy($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();
        return response()->json(['message' => 'Fournisseur deleted successfully']);
    }
}
