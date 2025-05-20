<?php

namespace App\Http\Controllers\Api;

use App\Models\Traitement;
use App\Models\Band;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class TraitementController extends Controller
{
    /**
     * Afficher tous les traitements d'une bande spécifique.
     */
    public function indexForBand($bandCode)
    {
        // Vérifie que la bande existe en fonction du code
        $band = Band::where('code', $bandCode)->firstOrFail();

        // Récupérer tous les traitements associés à la bande
        $traitements = $band->traitements; // On suppose qu'il y a une relation définie dans Band

        // Retourner la réponse sous forme de JSON
        return response()->json($traitements);
    }

    /**
     * Afficher un traitement spécifique.
     */
    public function show($bandCode, Traitement $traitement)
    {
        // Vérifier que le traitement appartient à la bande spécifiée
        if ($traitement->band->code !== $bandCode) {
            return response()->json(['message' => 'Traitement non trouvé pour cette bande'], 404);
        }

        // Retourner les détails du traitement avec la bande associée
        return response()->json($traitement->load('band'));
    }

    /**
     * Créer un nouveau traitement pour une bande spécifique.
     */
    public function storeForBand(Request $request, $bandCode)
    {
        // Validation des données
        $validatedData = $request->validate([
            'nom' => 'required|string|max:191',
            'description' => 'nullable|string',
            'date_planifiee' => 'required|date',
            'prix' => 'nullable|numeric|min:0',
            'recu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validation de l'image (2 Mo max)
        ]);
    
        // Vérifie que la bande existe en fonction du code
        $band = Band::where('code', $bandCode)->firstOrFail();
    
        // Ajouter l'id de la bande
        $validatedData['band_id'] = $band->id;
    
        // Traitement du fichier reçu s’il existe
        if ($request->hasFile('recu')) {
            // Enregistre l'image dans le dossier public/recu_traitements
            $imagePath = $request->file('recu')->store('recu_traitements', 'public');
            // Stocke le chemin relatif de l'image dans la base de données
            $validatedData['recu_image_path'] = $imagePath;
        }
    
        // Créer le traitement
        $traitement = Traitement::create($validatedData);
    
        // Retourner une réponse avec le traitement créé
        return response()->json($traitement, 201);
    }
    

    /**
     * Mettre à jour un traitement existant.
     */
    public function update(Request $request, $bandCode, Traitement $traitement)
    {
        // Vérifier que le traitement appartient à la bande spécifiée
        if ($traitement->band->code !== $bandCode) {
            return response()->json(['message' => 'Traitement non trouvé pour cette bande'], 404);
        }

        // Validation des données
        $validatedData = $request->validate([
            'band_id' => 'required|exists:bands,id', // Vérifie que la bande existe
            'nom' => 'required|string|max:191', // Validation du nom
            'description' => 'nullable|string', // Validation de la description
            'date_planifiee' => 'required|date', // Validation de la date
        ]);

        // Mettre à jour le traitement
        $traitement->update($validatedData);

        // Retourner la réponse sous forme de JSON avec les données mises à jour
        return response()->json($traitement);
    }

    /**
     * Supprimer un traitement.
     */
    public function destroy($bandCode, Traitement $traitement)
    {
        // Vérifier que le traitement appartient à la bande spécifiée
        if ($traitement->band->code !== $bandCode) {
            return response()->json(['message' => 'Traitement non trouvé pour cette bande'], 404);
        }

        // Supprimer le traitement
        $traitement->delete();

        // Retourner une réponse JSON avec un message de succès
        return response()->json(['message' => 'Traitement supprimé avec succès.']);
    }
}
