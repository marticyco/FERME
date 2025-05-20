<?php

// app/Http/Controllers/API/EntreeController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entree;
use App\Models\Aliment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntreeController extends Controller
{
    // Afficher la liste des entrées
    public function index()
    {
        $entrees = Entree::with('aliment', 'fournisseur')->get();
        return response()->json($entrees);
    }

    // Afficher une entrée spécifique
    public function show($id)
    {
        $entree = Entree::with('aliment', 'fournisseur')->findOrFail($id);
        return response()->json($entree);
    }

    // Créer une nouvelle entrée
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validated = $request->validate([
            'aliment_id' => 'required|exists:aliments,id', // Aliment doit exister
            'fournisseur_id' => 'required|exists:fournisseurs,id', // Fournisseur doit exister
            'quantite' => 'required|numeric|min:1', // Quantité doit être un nombre positif
            'date_entree' => 'nullable|date', // La date d'entrée est facultative et doit être valide si fournie
        ]);

        // Démarrer une transaction pour assurer l'intégrité des données
        DB::beginTransaction();

        try {
            // Créer l'entrée dans la base de données
            $entree = Entree::create($validated);

            // Récupérer l'aliment concerné
            $aliment = Aliment::find($validated['aliment_id']);

            // Vérifier si l'aliment existe
            if ($aliment) {
                // Récupérer le stock lié à cet aliment
                $stock = $aliment->stock;

                // Ajouter la quantité de l'entrée au stock existant
                $stock->quantite += $validated['quantite'];
                $stock->save(); // Sauvegarder le stock mis à jour
            } else {
                throw new \Exception('L\'aliment n\'existe pas dans la base de données.');
            }

            // Valider la transaction
            DB::commit();

            // Retourner la réponse JSON avec les données mises à jour
        return response()->json([
            'success' => true,
            'new_stock' => $stock->quantite,  // Nouveaux stocks après mise à jour
            'entree' => $entree,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Erreur lors de la création'], 500);
    }
    
    }

    // Mettre à jour une entrée existante
    public function update(Request $request, $id)
    {
        // Récupérer l'entrée existante
        $entree = Entree::findOrFail($id);

        // Mettre à jour les données de l'entrée
        $entree->update($request->all());

        // Retourner la réponse avec l'entrée mise à jour
        return response()->json($entree);
    }

    // Supprimer une entrée
    public function destroy($id)
    {
        // Récupérer l'entrée existante
        $entree = Entree::findOrFail($id);

        // Démarrer une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        try {
            // Récupérer l'aliment lié à cette entrée
            $aliment = $entree->aliment;

            // Récupérer le stock de l'aliment
            $stock = $aliment->stock;

            // Réduire la quantité dans le stock du montant de l'entrée
            $stock->quantite -= $entree->quantite;

            // Sauvegarder les modifications du stock
            $stock->save();

            // Supprimer l'entrée
            $entree->delete();

            // Valider la transaction
            DB::commit();

            // Retourner un message de succès
            return response()->json(['message' => 'Entrée supprimée avec succès']);
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();

            // Retourner une réponse d'erreur
            return response()->json(['error' => 'Une erreur est survenue lors de la suppression de l\'entrée.'], 500);
        }
    }
}
