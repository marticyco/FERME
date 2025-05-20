<?php

// app/Http/Controllers/API/SortieController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sortie;
use App\Models\Aliment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SortieController extends Controller
{
    // Afficher la liste des sorties
    public function index()
    {
        $sorties = Sortie::with('aliment')->get();
        return response()->json($sorties);
    }

    // Afficher une sortie spécifique
    public function show($id)
    {
        $sortie = Sortie::with('aliment')->findOrFail($id);
        return response()->json($sortie);
    }

    // Créer une nouvelle sortie
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validated = $request->validate([
            'aliment_id' => 'required|exists:aliments,id', // Aliment doit exister
            'quantite' => 'required|numeric|min:1', // Quantité doit être un nombre positif
            'date_sortie' => 'nullable|date',
        ]);

        // Démarrer une transaction pour assurer l'intégrité des données
        DB::beginTransaction();

        try {
            // Créer la sortie dans la base de données
            $sortie = Sortie::create($validated);

            // Récupérer l'aliment concerné
            $aliment = Aliment::find($validated['aliment_id']);

            // Vérifier si l'aliment existe
            if (!$aliment) {
                // Si l'aliment n'existe pas, annuler la transaction et retourner une erreur
                DB::rollBack();
                return response()->json(['error' => 'L\'aliment n\'existe pas.'], 400);
            }

            // Récupérer le stock lié à cet aliment
            $stock = $aliment->stock;

            // Vérifier si la quantité disponible est suffisante
            if ($stock->quantite < $validated['quantite']) {
                // Si la quantité est insuffisante, annuler la transaction et retourner une erreur
                DB::rollBack();
                return response()->json(['error' => 'Quantité de stock insuffisante.'], 400);
            }

            // Réduire la quantité du stock en fonction de la sortie
            $stock->quantite -= $validated['quantite'];
            $stock->save(); // Sauvegarder le stock mis à jour

            // Valider la transaction
            DB::commit();

            // Retourner la réponse avec la sortie créée
            return response()->json($sortie, 201);
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();

            // Retourner une réponse d'erreur
            return response()->json(['error' => 'Une erreur est survenue lors de la création de la sortie.'], 500);
        }
    }

    // Mettre à jour une sortie existante
    public function update(Request $request, $id)
    {
        // Récupérer la sortie existante
        $sortie = Sortie::findOrFail($id);

        // Mettre à jour les données de la sortie
        $sortie->update($request->all());

        // Retourner la réponse avec la sortie mise à jour
        return response()->json($sortie);
    }

    // Supprimer une sortie
    public function destroy($id)
    {
        // Démarrer une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        try {
            // Récupérer la sortie existante
            $sortie = Sortie::findOrFail($id);

            // Récupérer l'aliment lié à cette sortie
            $aliment = $sortie->aliment;

            // Récupérer le stock de l'aliment
            $stock = $aliment->stock;

            // Ajouter la quantité de sortie au stock
            $stock->quantite += $sortie->quantite;

            // Sauvegarder les modifications du stock
            $stock->save();

            // Supprimer la sortie
            $sortie->delete();

            // Valider la transaction
            DB::commit();

            // Retourner un message de succès
            return response()->json(['message' => 'Sortie supprimée avec succès']);
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();

            // Retourner une réponse d'erreur
            return response()->json(['error' => 'Une erreur est survenue lors de la suppression de la sortie.'], 500);
        }
    }
}
