<?php

// app/Http/Controllers/API/AlimentController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aliment;
use Illuminate\Http\Request;

class AlimentController extends Controller
{
    // Afficher la liste des aliments
    public function index()
    {
        $aliments = Aliment::all();
        return response()->json($aliments);
    }

    // Afficher un aliment spécifique
    public function show($id)
    {
        $aliment = Aliment::findOrFail($id);
        return response()->json($aliment);
    }

    // Créer un nouvel aliment
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validated = $request->validate([
            'nom' => 'required|string',
            'type' => 'required|string',
            'prix_unitaire' => 'required|numeric',
        ]);

        // Création de l'aliment
        $aliment = Aliment::create($validated);

        // Retourne la réponse avec l'aliment créé
        return response()->json($aliment, 201);
    }

    // Mettre à jour un aliment existant
    public function update(Request $request, $id)
    {
        // Trouver l'aliment
        $aliment = Aliment::findOrFail($id);
        $aliment->update($request->all());

        // Retourner l'aliment mis à jour
        return response()->json($aliment);
    }

    // Supprimer un aliment
    public function destroy($id)
    {
        // Trouver l'aliment
        $aliment = Aliment::findOrFail($id);

        // Supprimer l'aliment
        $aliment->delete();

        return response()->json(['message' => 'Aliment deleted successfully']);
    }

    // Optionnel: Mettre à jour le prix ou les informations d'un aliment
    // Cette méthode peut être appelée lors d'une modification d'aliment
    public function updatePrice(Request $request, $id)
    {
        $aliment = Aliment::findOrFail($id);
        $aliment->update(['prix_unitaire' => $request->input('prix_unitaire')]);

        return response()->json($aliment);
    }
}

