<?php

namespace App\Http\Controllers\Api;

use App\Models\Incubation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;  // Importer la classe Response de Laravel

class IncubationController extends Controller
{
    /**
     * Afficher la liste de toutes les incubations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $incubations = Incubation::all();

        return response()->json($incubations, Response::HTTP_OK); 
    }

    /**
     * Afficher les détails d'une incubation spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $incubation = Incubation::find($id);

        if (!$incubation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incubation non trouvée.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $incubation,
        ]);
    }

    /**
     * Créer une nouvelle incubation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date_format:Y-m-d',
            'nombre_oeufs_incubes' => 'required|integer|min:1',
            'temperature' => 'required|numeric',
            'humidite' => 'required|numeric',
        ]);

        // Utiliser DateTime au lieu de Carbon
        $date_debut = new \DateTime($request->date_debut);
        
        // Calcul des dates de mirage et de fin d'éclosion en utilisant DateTime::modify
        $date_mirage = (clone $date_debut)->modify('+10 days');
        $date_fin_eclosion = (clone $date_debut)->modify('+22 days');

        $incubation = Incubation::create([
            'date_debut' => $date_debut->format('Y-m-d'),
            'nombre_oeufs_incubes' => $request->nombre_oeufs_incubes,
            'temperature' => $request->temperature,
            'humidite' => $request->humidite,
            'date_mirage' => $date_mirage->format('Y-m-d'),
            'date_fin_eclosion' => $date_fin_eclosion->format('Y-m-d'),
            'nombre_oeufs_fertiles' => $request->nombre_oeufs_fertiles ?? null,
            'nombre_oeufs_fertiles_eclos' => $request->nombre_oeufs_fertiles_eclos ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $incubation,
            'message' => 'Incubation créée avec succès.',
        ], 201);
    }

    /**
     * Mettre à jour une incubation existante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validation des données de mise à jour
        $request->validate([
            'date_debut' => 'required|date_format:Y-m-d',
            'nombre_oeufs_incubes' => 'required|integer|min:1',
            'temperature' => 'required|numeric',
            'humidite' => 'required|numeric',
        ]);
 
        // Récupération de l'incubation par son ID
        $incubation = Incubation::find($id);
 
        // Vérification si l'incubation existe
        if (!$incubation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incubation non trouvée.',
            ], 404);
        }

        // Utiliser DateTime au lieu de Carbon
        $date_debut = new \DateTime($request->date_debut);
        
        // Calcul des nouvelles dates de mirage et de fin d'éclosion en utilisant DateTime::modify
        $date_mirage = (clone $date_debut)->modify('+10 days');
        $date_fin_eclosion = (clone $date_debut)->modify('+22 days');

        // Mise à jour des données de l'incubation
        $incubation->update([
            'date_debut' => $date_debut->format('Y-m-d'),
            'nombre_oeufs_incubes' => $request->nombre_oeufs_incubes,
            'temperature' => $request->temperature,
            'humidite' => $request->humidite,
            'date_mirage' => $date_mirage->format('Y-m-d'),
            'date_fin_eclosion' => $date_fin_eclosion->format('Y-m-d'),
            'nombre_oeufs_fertiles' => $request->nombre_oeufs_fertiles ?? null,
            'nombre_oeufs_fertiles_eclos' => $request->nombre_oeufs_fertiles_eclos ?? null,
        ]);
 
        return response()->json([
            'status' => 'success',
            'data' => $incubation,
            'message' => 'Incubation mise à jour avec succès.',
        ], 200);
    }

    /**
 * Mettre à jour le nombre d'œufs fertiles d'une incubation.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function updateFertiles(Request $request, $id)
{
    // Validation uniquement du nombre d'œufs fertiles
    $request->validate([
        'nombre_oeufs_fertiles' => 'required|integer|min:0',
    ]);

    // Recherche de l'incubation par ID
    $incubation = Incubation::find($id);

    // Vérification si l'incubation existe
    if (!$incubation) {
        return response()->json([
            'status' => 'error',
            'message' => 'Incubation non trouvée.',
        ], 404);
    }

    // Mise à jour du nombre d'œufs fertiles, sans modifier les autres champs
    $incubation->nombre_oeufs_fertiles = $request->nombre_oeufs_fertiles;

    // Calculer le taux de fertilité
    $tauxFertilite = $incubation->calculateTauxFertilite(); 

    // Mettre à jour le taux de fertilité dans l'objet
    $incubation->taux_fertilite = $tauxFertilite;

    // Sauvegarde de l'incubation mise à jour
    $incubation->save();

    // Retour de la réponse avec les données mises à jour
    return response()->json([
        'status' => 'success',
        'data' => $incubation,
        'message' => 'Nombre d\'œufs fertiles et taux de fertilité mis à jour.',
    ]);
}




    /**
     * Mettre à jour le nombre d'œufs éclos d'une incubation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEclosion(Request $request, $id)
{
    // Validation uniquement du nombre d'œufs éclos
    $request->validate([
        'nombre_oeufs_fertiles_eclos' => 'required|integer|min:0',
    ]);

    // Recherche de l'incubation par ID
    $incubation = Incubation::find($id);

    // Vérification si l'incubation existe
    if (!$incubation) {
        return response()->json([
            'status' => 'error',
            'message' => 'Incubation non trouvée.',
        ], 404);
    }

    // Mise à jour du nombre d'œufs éclos, sans modifier les autres champs
    $incubation->nombre_oeufs_fertiles_eclos = $request->nombre_oeufs_fertiles_eclos;

    // Calculer le taux d'éclosion
    $tauxEclosion = $incubation->calculateTauxEclosion(); 

    // Mettre à jour le taux d'éclosion dans l'objet
    $incubation->taux_eclosion = $tauxEclosion;

    // Sauvegarde de l'incubation mise à jour
    $incubation->save();

    // Retour de la réponse avec les données mises à jour
    return response()->json([
        'status' => 'success',
        'data' => $incubation,
        'message' => 'Nombre d\'œufs éclos et taux d\'éclosion mis à jour.',
    ]);
}


    /**
     * Vérifier les alertes de taux de fertilité et d'éclosion.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAlerts()
    {
        $alerts = [];

        // Récupérer les incubations avec les dates de mirage et de fin d'éclosion dans les 2 prochains jours
        $incubations = Incubation::where(function($query) {
                $query->where('date_mirage', '>', now())
                      ->where('date_mirage', '<=', now()->addDays(2))
                      ->orWhere('date_fin_eclosion', '>', now())
                      ->where('date_fin_eclosion', '<=', now()->addDays(2));
            })
            ->get();

        foreach ($incubations as $incubation) {
            // Vérification du taux de fertilité
            if ($incubation->calculateTauxFertilite() < 80) {
                $alerts[] = "Taux de fertilité non acceptable pour l'incubation #{$incubation->id}";
            }

            // Vérification du taux d'éclosion
            if ($incubation->calculateTauxEclosion() < 80) {
                $alerts[] = "Taux d'éclosion non acceptable pour l'incubation #{$incubation->id}";
            }
        }

        return response()->json([
            'status' => 'success',
            'alerts' => $alerts,
        ]);
    }

    /**
     * Supprimer une incubation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $incubation = Incubation::findOrFail($id); // Trouver l'incubation par ID
            $incubation->delete(); // Supprimer l'incubation
            return response()->json(['message' => 'Incubation supprimée avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Incubation non trouvée ou erreur lors de la suppression.'], 404);
        }
    }
}

