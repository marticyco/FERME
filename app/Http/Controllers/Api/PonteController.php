<?php

namespace App\Http\Controllers\Api;

use App\Models\Lot;
use App\Models\Mortalite;
use App\Models\Ponte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PonteController extends Controller
{
    /**
     * Enregistrer la mortalité pour un lot spécifique.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lotId
     * @return \Illuminate\Http\Response
     */
    public function storeMortalite(Request $request, $lotId)
{
    // Valider les données de la requête
    $validated = $request->validate([
        'date_mortalite' => 'required|date',
        'nombre_males_morts' => 'required|integer|min:0',
        'nombre_femelles_morts' => 'required|integer|min:0',
    ]);

    // Trouver le lot avec l'ID fourni
    $lot = Lot::findOrFail($lotId);

    // Créer un enregistrement de mortalité
    $mortalite = new Mortalite();
    $mortalite->lot_id = $lot->id;
    $mortalite->date_mortalite = $validated['date_mortalite'];
    $mortalite->nombre_males_morts = $validated['nombre_males_morts'];
    $mortalite->nombre_femelles_morts = $validated['nombre_femelles_morts'];
    
    // Calculer le nombre total de morts (nombre_morts)
    $mortalite->nombre_morts = $validated['nombre_males_morts'] + $validated['nombre_femelles_morts'];

    // Sauvegarder la mortalité
    $mortalite->save();

    // Mettre à jour le nombre de mâles et de femelles dans le lot
    $nouveau_nombre_males = $lot->nombre_males - $validated['nombre_males_morts'];
    $nouveau_nombre_femelles = $lot->nombre_femelles - $validated['nombre_femelles_morts'];

    // Mise à jour des champs dans le lot
    $lot->update([
        'nombre_males' => $nouveau_nombre_males,
        'nombre_femelles' => $nouveau_nombre_femelles,
    ]);

    // Retourner une réponse JSON
    return response()->json([
        'message' => 'Mortalité enregistrée et lot mis à jour avec succès!',
        'data' => $mortalite,
    ]);
}
       

    /**
     * Enregistrer la ponte pour un lot spécifique.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lotId
     * @return \Illuminate\Http\Response
     */
    public function storePonte(Request $request, $lotId)
{
    // Valider les données de la requête
    $validated = $request->validate([
        'date_ponte' => 'required|date',
        'nombre_oeufs' => 'required|integer|min:0',
    ]);

    // Trouver le lot avec l'ID fourni
    $lot = Lot::findOrFail($lotId);

    // Vérifier que le nombre de femelles est disponible
    if (!$lot->nombre_femelles) {
        return response()->json([
            'message' => 'Le nombre de femelles dans le lot est requis pour calculer le taux de ponte.',
        ], 400);
    }

    // Calculer le taux de ponte (nombre d'œufs pondus / nombre de femelles)
    $taux_ponte = ($validated['nombre_oeufs'] / $lot->nombre_femelles) * 100;

    // Créer un enregistrement de ponte
    $ponte = new Ponte();
    $ponte->lot_id = $lot->id; // Lien avec le lot
    $ponte->date_ponte = $validated['date_ponte']; // Date de la ponte
    $ponte->nombre_oeufs = $validated['nombre_oeufs']; // Nombre d'œufs pondus
    $ponte->taux_ponte = round($taux_ponte, 2); // Taux de ponte (arrondi à 2 décimales)
    $ponte->save();

    // Retourner une réponse JSON
    return response()->json([
        'message' => 'Ponte enregistrée avec succès!',
        'data' => $ponte,
    ]);
}



    /**
 * Récupérer la liste des pontes pour un lot spécifique.
 *
 * @param  int  $lotId
 * @return \Illuminate\Http\Response
 */
public function getPontesByLot($lotId)
{
    // Trouver le lot avec l'ID fourni
    $lot = Lot::findOrFail($lotId);

    // Récupérer toutes les pontes associées à ce lot
    $pontes = Ponte::where('lot_id', $lot->id)->get();

    // Retourner une réponse JSON avec les pontes
    return response()->json([
        'message' => 'Liste des pontes récupérée avec succès!',
        'data' => $pontes,
    ]);
}



public function getOverviewData($lotId)
{
    try {
        // Récupérer le lot avec ses informations spécifiques
        $lot = Lot::findOrFail($lotId);

        // Récupérer toutes les pontes pour le lot spécifié
        $ponteRecords = Ponte::where('lot_id', $lotId)->get();

        // Calculer le total des œufs pondus
        $totalOeufs = $ponteRecords->sum('nombre_oeufs');

        // Calculer le taux de ponte moyen
        $totalFemelles = $lot->nombre_femelles;
        $totalTauxPonte = 0;
        $totalRecords = $ponteRecords->count();

        // Calculer le taux de ponte pour chaque enregistrement
        foreach ($ponteRecords as $ponte) {
            $tauxPonte = ($totalFemelles > 0) ? ($ponte->nombre_oeufs / $totalFemelles) * 100 : 0;
            $totalTauxPonte += $tauxPonte;
        }

        // Calculer le taux moyen de ponte
        $moyenneTauxPonte = ($totalRecords > 0) ? ($totalTauxPonte / $totalRecords) : 0;

        // Préparer les données à retourner
        $overviewData = [
            'lotNom' => $lot->nom,
            'nombre_males' => $lot->nombre_males,
            'nombre_femelles' => $lot->nombre_femelles,
            'total_oeufs' => $totalOeufs,
            'moyenne_taux_ponte' => round($moyenneTauxPonte, 2), // Taux moyen arrondi à 2 décimales
        ];

        // Retourner les données dans une réponse JSON
        return response()->json([
            'message' => 'Données de la vue d\'ensemble récupérées avec succès.',
            'data' => $overviewData
        ], 200);
    } catch (\Exception $e) {
        // En cas d'erreur, retourner une réponse d'erreur
        return response()->json([
            'message' => 'Erreur lors de la récupération des données de la vue d\'ensemble.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



public function getTotalOeufs()
{
    try {
        $totalOeufs = \App\Models\Ponte::sum('nombre_oeufs');
        $moyenneTauxPonte = \App\Models\Ponte::avg('taux_ponte'); // Moyenne sur tous les enregistrements

        return response()->json([
            'total_oeufs' => $totalOeufs,
            'moyenne_taux_ponte' => round($moyenneTauxPonte, 2) // 2 chiffres après la virgule
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de la récupération des données de ponte.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
