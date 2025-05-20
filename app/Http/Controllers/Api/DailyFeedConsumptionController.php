<?php
namespace App\Http\Controllers\Api;

use App\Models\DailyFeedConsumption;
use App\Models\Band;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;


class DailyFeedConsumptionController extends Controller
{
    /**
     * Affiche une liste de toutes les consommations d'aliments pour une bande spécifique.
     *
     * @param int $bandId
     * @return \Illuminate\Http\JsonResponse
     */
   
    
    public function index()
    {
        // Récupérer toutes les consommations d'aliment avec leur bande associée
        $consommations = DailyFeedConsumption::with('band')->get();
    
        // Retourner la réponse en JSON avec les données des consommations
        return response()->json($consommations, Response::HTTP_OK);
    }
    
    /**
     * Enregistre une nouvelle consommation d'aliments dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $bandId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $bandId)
    {
        // Validation des données
        $request->validate([
            'date' => 'required|date', // Date de consommation requise
            'quantite_aliment' => 'required|numeric|min:0', // Quantité d'aliments requise
        ]);

        // Création d'une nouvelle consommation d'aliments associée à la bande
        $consommation = DailyFeedConsumption::create([
            'band_id' => $bandId,
            'date' => $request->date,
            'quantite_aliment' => $request->quantite_aliment,
        ]);

        return response()->json($consommation, Response::HTTP_CREATED); // Retourner la consommation créée
    }

    /**
     * Affiche les détails d'une consommation d'aliments spécifique.
     *
     * @param int $bandId
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($bandId, $id)
    {
        $consommation = DailyFeedConsumption::findOrFail($id); // Récupérer la consommation par ID
        return response()->json($consommation, Response::HTTP_OK); // Retourner les détails de la consommation
    }

    /**
     * Met à jour une consommation d'aliments existante dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $bandId
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $bandId, $id)
    {
        // ✅ Validation des données
        $request->validate([
            'band_id' => 'required|exists:bands,id', // Optionnel si tu veux vérifier la cohérence de l'association
            'date' => 'required|date', // Date de consommation requise
            'quantite_aliment' => 'required|numeric|min:0', // Quantité d'aliment requise
        ]);
    
        // ✅ On vérifie que la consommation existe et qu'elle appartient à la bonne bande
        $consommation = DailyFeedConsumption::where('id', $id)
            ->where('band_id', $bandId)
            ->first();
    
        if (!$consommation) {
            return response()->json(['message' => 'Consommation non trouvée.'], 404);
        }
    
        // ✅ Mise à jour de la consommation
        $consommation->update($request->all());
    
        // ✅ Retourner la consommation mise à jour
        return response()->json($consommation, Response::HTTP_OK);
    }
    

    /**
     * Supprime une consommation d'aliments spécifique de la base de données.
     *
     * @param int $bandId
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
  
    
    public function destroy($bandId, $id)
    {
        // Rechercher la bande
        $band = Band::findOrFail($bandId);
    
        // Rechercher la consommation d'aliment liée à cette bande
        $consommation = $band->feedConsumptions()->where('id', $id)->first();
    
        // Vérifier si la consommation existe
        if (!$consommation) {
            return response()->json(['message' => 'Consommation d\'aliments non trouvée.'], 404);
        }
    
        // Supprimer la consommation
        $consommation->delete();
    
        // Retourner une réponse JSON
        return response()->json(['message' => 'Consommation d\'aliments supprimée avec succès.'], Response::HTTP_NO_CONTENT);
    }
    
}
