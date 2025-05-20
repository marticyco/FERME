<?php

// app/Http/Controllers/API/StockController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Entree;
use App\Models\Sortie;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Afficher la liste des stocks
    public function index()
    {
        $stocks = Stock::with('aliment')->get();
        return response()->json($stocks);
    }

    // Afficher un stock spécifique
    public function show($id)
    {
        $stock = Stock::with('aliment')->findOrFail($id);
        return response()->json($stock);
    }

    // Créer une entrée en stock (pour augmenter le stock)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aliment_id' => 'required|exists:aliments,id', // Vérifie que l'aliment existe
            'quantite' => 'required|numeric|min:1', // Quantité doit être positive
            'date_entree' => 'required|date', // Vérifie le format de la date
        ]);

        try {
            // Créer l'entrée en stock
            $stock = Stock::create([
                'aliment_id' => $validated['aliment_id'],
                'quantite' => $validated['quantite'],
                'date_entree' => $validated['date_entree'],
            ]);

            // Retourner la réponse avec le stock créé
            return response()->json($stock, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création du stock.'], 500);
        }
    }

    // Mettre à jour un stock existant
    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $stock->update($request->all());

        // Retourner le stock mis à jour
        return response()->json($stock);
    }

    // Supprimer un stock
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return response()->json(['message' => 'Stock deleted successfully']);
    }

    // Mettre à jour le stock après une entrée
    public function updateAfterEntree($aliment_id, $quantite)
    {
        // Récupérer le stock pour l'aliment
        $stock = Stock::where('aliment_id', $aliment_id)->first();

        if ($stock) {
            // Mettre à jour la quantité du stock
            $stock->quantite += $quantite;
            $stock->save();

            return response()->json($stock);
        } else {
            // Si le stock n'existe pas encore, créer un nouveau stock
            $newStock = Stock::create([
                'aliment_id' => $aliment_id,
                'quantite' => $quantite,
                'date_entree' => now(), // La date d'entrée actuelle
            ]);

            return response()->json($newStock, 201);
        }
    }

    // Mettre à jour le stock après une sortie
    public function updateAfterSortie($aliment_id, $quantite)
    {
        // Récupérer le stock pour l'aliment
        $stock = Stock::where('aliment_id', $aliment_id)->first();

        if ($stock && $stock->quantite >= $quantite) {
            // Réduire la quantité du stock
            $stock->quantite -= $quantite;
            $stock->save();

            return response()->json($stock);
        } else {
            // Si le stock est insuffisant
            return response()->json(['error' => 'Stock insuffisant.'], 400);
        }
    }


    public function lowStock()
{
    $lowStocks = Stock::with('aliment')
        ->where('quantite', '<', 50)
        ->get();

    return response()->json($lowStocks);
}


}
