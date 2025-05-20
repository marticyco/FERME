<?php

namespace App\Http\Controllers\Api;

use App\Models\DailyMortality;
use App\Models\Band;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DailyMortalityController extends Controller
{
    /**
     * Afficher la liste des mortalités journalières.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer toutes les mortalités avec leur bande associée
        $mortalites = DailyMortality::with('band')->get();

        // Retourner la réponse en JSON avec les données des mortalités
        return response()->json($mortalites, Response::HTTP_OK);
    }

    /**
     * Créer une nouvelle mortalité journalière.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation des données entrantes
        $request->validate([
            'band_id' => 'required|exists:bands,id', // Vérifier que la bande existe
            'date' => 'required|date',
            'mortalite_journaliere' => 'required|integer|min:0',
        ]);

        // Créer la mortalité journalière
        $mortalite = DailyMortality::create($request->all());

        // Retourner la réponse en JSON avec la mortalité créée
        return response()->json($mortalite, Response::HTTP_CREATED);
    }

    /**
     * Afficher les détails d'une mortalité journalière spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Récupérer la mortalité spécifique avec la bande associée
        $mortalite = DailyMortality::with('band')->findOrFail($id);

        // Retourner la réponse en JSON avec les détails de la mortalité
        return response()->json($mortalite, Response::HTTP_OK);
    }

    public function update(Request $request, $bandId, $id)
{
    $request->validate([
        'band_id' => 'required|exists:bands,id',
        'date' => 'required|date',
        'mortalite_journaliere' => 'required|integer|min:0',
    ]);

    $mortality = DailyMortality::where('id', $id)
        ->where('band_id', $bandId)
        ->first();

    if (!$mortality) {
        return response()->json(['message' => 'Mortalité non trouvée.'], 404);
    }

    $mortality->update($request->all());

    return response()->json($mortality, Response::HTTP_OK);
}


    public function destroy($bandId, $id)
{
    $band = Band::findOrFail($bandId);
    $mortality = $band->mortalites()->where('id', $id)->first();

    if (!$mortality) {
        return response()->json(['message' => 'Mortalité non trouvée.'], 404);
    }

    $mortality->delete();

    return response()->json(['message' => 'Mortalité supprimée avec succès.'], Response::HTTP_NO_CONTENT);
}

}
