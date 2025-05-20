<?php

namespace App\Http\Controllers\Api;

use App\Models\Band;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Carbon\Carbon;

class BandController extends Controller
{
    /**
     * Affiche une liste de toutes les bandes sans les mortalités.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer toutes les bandes sans leurs mortalités
        $bands = Band::all();

        // Retourner toutes les bandes en format JSON
        return response()->json($bands, Response::HTTP_OK); 
    }

    /**
     * Créer une nouvelle bande dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'compte_initial' => 'required|integer|min:0',
            'date_debut' => 'required|date',
        ]);

        // Créer la nouvelle bande
        $band = Band::create($request->all());
        $band->compte_actuel = $band->compte_initial;
        $band->save();

        // Retourner la réponse JSON de la bande créée
        return response()->json($band, Response::HTTP_CREATED);
    }

    /**
     * Affiche les détails d'une bande spécifique.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $band = Band::findOrFail($id);

        // Retourner les détails de la bande spécifique en format JSON
        return response()->json($band, Response::HTTP_OK);
    }

    public function showBandeDetails($id)
    {
        $band = Band::find($id);
        
        if ($band) {
            // Récupération de la mortalité totale pour cette bande
            $mortaliteTotale = $band->mortalite_totale;
            $quantiteAlimentTotale = $band->quantite_aliment_totale;
    
            // Retourner la bande et la mortalité totale en format JSON
            return response()->json([
                'band' => $band,
                'mortalite_totale' => $mortaliteTotale,
                'quantite_aliment_totale' => $quantiteAlimentTotale,
            ], 200); // Statut HTTP 200 pour succès
        } else {
            // Retourner une réponse JSON en cas d'erreur (bande non trouvée)
            return response()->json([
                'error' => 'Bande non trouvée.',
            ], 404); // Statut HTTP 404 pour non trouvé
        }
    }


    /**
     * Met à jour une bande existante.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'compte_initial' => 'required|integer|min:0',
            'date_debut' => 'required|date',
        ]);

        // Trouver la bande et la mettre à jour
        $band = Band::findOrFail($id);
        $band->update($request->all());

        // Retourner la bande mise à jour en format JSON
        return response()->json($band, Response::HTTP_OK);
    }

    /**
     * Supprimer une bande.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $band = Band::findOrFail($id);
        $band->delete();

        // Retourner une réponse JSON confirmant la suppression
        return response()->json(['message' => 'Bande supprimée avec succès.'], Response::HTTP_NO_CONTENT);
    }


    public function countBandsStartedThisMonth()
{
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();

    $count = Band::whereBetween('date_debut', [$startOfMonth, $endOfMonth])->count();

    return response()->json(['count' => $count]);

}

public function listBandsThisMonth()
{
    $now = now();
    $bands = Band::whereMonth('date_debut', $now->month)
                 ->whereYear('date_debut', $now->year)
                 ->select('id', 'code', 'date_fin') // tu peux ajouter + de champs si besoin
                 ->orderBy('date_fin', 'asc')
                 ->get();

    return response()->json(['bands' => $bands]);
}



}
