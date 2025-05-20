<?php

// app/Http/Controllers/Api/LotController.php

namespace App\Http\Controllers\Api;

use App\Models\Lot;
use App\Models\Mortalite;
use App\Models\Ponte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;  // Importer la classe Response de Laravel

class LotController extends Controller
{
    /**
     * Récupérer la liste de tous les lots
     */
    public function index()
{
    // Récupérer les lots avec leurs relations 'mortalites'
    $lots = Lot::all();

    // Ajouter les données calculées de mortalité totale et taux de mortalité
    $lotsWithStats = $lots->map(function ($lot) {
        return [
            'id' => $lot->id,
            'nom' => $lot->nom,
            'nombre_males' => $lot->nombre_males,
            'nombre_femelles' => $lot->nombre_femelles,
            'totalMortalite' => $lot->mortaliteTotale(),
            'tauxMortalite' => $lot->tauxMortalite(),
        ];
    });

    return response()->json($lotsWithStats, 200);
}


    /**
     * Récupérer un lot spécifique
     */
    public function showLot($lotId)  // Utilisation de $lotId au lieu de $lot_id
    {
        $lot = Lot::findOrFail($lotId);  // Modification ici aussi
        return response()->json($lot, 200);
    }

    /**
     * Créer un nouveau lot
     */
    public function storeLot(Request $request)
    {
        // Validation des données de la requête
        $validated = $request->validate([
            'nom' => 'required|string|max:191',
            'nombre_males' => 'required|integer|min:1',
            'nombre_femelles' => 'required|integer|min:1',
        ]);

        // Création du lot
        $lot = Lot::create($validated);

        return response()->json($lot, 201);
    }

    /**
     * Mettre à jour un lot existant
     */
    public function update(Request $request, $id)  // Utilisation de $lotId au lieu de $lot_id
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:191',
            'nombre_males' => 'required|integer|min:1',
            'nombre_femelles' => 'required|integer|min:1',
        ]);

        $lot = Lot::findOrFail($id);  // Modification ici aussi
        $lot->update($validated);

        return response()->json($lot, 200);
    }

    /**
     * Supprimer un lot
     */
    public function destroy($id)  // Utilisation de $lotId au lieu de $lot_id
    {
        $lot = Lot::findOrFail($id);  // Modification ici aussi
        $lot->delete();

        return response()->json(['message' => 'Lot supprimé avec succès'], 200);
    }

     /**
     * Récupérer les performances d'un lot
     */
    public function getLotPerformance($lotId)
    {
        // Trouver le lot par son ID
        $lot = Lot::findOrFail($lotId);  // Utilisation de $lotId au lieu de $lot_id

        // Récupérer les performances globales du lot
        $performance = $lot->getPerformance($lotId); // Appel de la méthode getPerformance() sur l'objet $lot

        // Retourner les performances en format JSON
        return response()->json($performance);

    }

    public function getPonteRecords($lotId)
{
    $lot = Lot::findOrFail($lotId);
    $ponteRecords = Ponte::where('lot_id', $lotId)->get();

    // Calculer le taux de ponte pour chaque ponte et l'ajouter à chaque objet
    foreach ($ponteRecords as $ponte) {
        // Utilisation de la méthode dailyEggRate pour calculer le taux de ponte
        $tauxPonte = $this->dailyEggRate($lotId);
        
        // Si taux_ponte n'est pas calculé, on définit une valeur par défaut
        $ponte->taux_ponte = $tauxPonte !== null ? $tauxPonte : 0;
    }

    return response()->json([
        'message' => 'Liste des pontes récupérée avec succès!',
        'data' => $ponteRecords
    ]);
}

    
     
    

    



}

