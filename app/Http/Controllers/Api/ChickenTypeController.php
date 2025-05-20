<?php

namespace App\Http\Controllers\Api;

use App\Models\ChickenType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChickenTypeController extends Controller
{
    /**
     * Récupérer la liste de tous les types de poulet.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer tous les types de poulet
        $chickenTypes = ChickenType::all();

        // Retourner les types de poulet sous forme de réponse JSON
        return response()->json($chickenTypes);
    }

    /**
     * Récupérer les détails d'un type de poulet spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Trouver le type de poulet avec l'ID
        $chickenType = ChickenType::find($id);

        // Vérifier si le type de poulet existe
        if (!$chickenType) {
            return response()->json(['message' => 'Type de poulet introuvable'], 404);
        }

        // Retourner le type de poulet en réponse JSON
        return response()->json($chickenType);
    }

    /**
     * Créer un nouveau type de poulet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        try {
            // Validation des données, incluant le champ 'price'
            $request->validate([
                'name' => 'required|string|max:191',
                'description' => 'nullable|string',
                'type' => 'required|string|in:frais,roti,fume',
                'weight' => 'required|numeric|in:0.8,0.9,1.0,1.2,1.5',
                'price' => 'required|numeric', // Validation du prix
            ]);
    
            // Log des données reçues
            \Log::info('Validation réussie. Création du type de poulet.');
    
            // Création d'une nouvelle instance de ChickenType
            $chickenType = new ChickenType([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'weight' => $request->weight,
                'price' => $request->price,  // Ajout du champ price
            ]);
    
            // Sauvegarder le type de poulet avec son prix
            $chickenType->save();
    
            return response()->json($chickenType, 201);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'enregistrement du type de poulet: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de l\'enregistrement du type de poulet'], 500);
        }
    }

    /**
     * Mettre à jour un type de poulet existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Log des données reçues
            \Log::info('Données reçues:', $request->all());
            
            // Validation des données, incluant le champ 'price'
            $request->validate([
                'name' => 'required|string|max:191',
                'description' => 'nullable|string',
                'type' => 'required|string|in:frais,roti,fume',
                'weight' => 'required|numeric|in:0.8,0.9,1.0,1.2,1.5',
                'price' => 'required|numeric', // Validation du prix
            ]);

            // Trouver le type de poulet existant
            $chickenType = ChickenType::findOrFail($id);

            // Mise à jour des données du type de poulet
            $chickenType->name = $request->name;
            $chickenType->description = $request->description;
            $chickenType->type = $request->type;
            $chickenType->weight = $request->weight;
            $chickenType->price = $request->price;  // Mise à jour du prix

            // Enregistrer les modifications dans la base de données
            $chickenType->save();

            // Retourner la réponse avec le type de poulet mis à jour
            return response()->json($chickenType, 200);

        } catch (\Exception $e) {
            // Log l'erreur pour plus de détails
            \Log::error('Erreur lors de la mise à jour du type de poulet : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour du type de poulet'], 500);
        }
    }

    /**
     * Supprimer un type de poulet.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Trouver le type de poulet avec l'ID
        $chickenType = ChickenType::find($id);

        // Vérifier si le type de poulet existe
        if (!$chickenType) {
            return response()->json(['message' => 'Type de poulet introuvable'], 404);
        }

        // Supprimer le type de poulet
        $chickenType->delete();

        // Retourner une réponse indiquant que la suppression a réussi
        return response()->json(['message' => 'Type de poulet supprimé avec succès']);
    }
}
