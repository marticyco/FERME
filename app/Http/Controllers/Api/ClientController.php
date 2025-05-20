<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Subscription;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all(); // Récupérer tous les clients
        return response()->json($clients); // Retourner les clients sous forme de JSON
    }

    /**
     * Afficher les détails d'un client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::findOrFail($id); // Trouver un client par son ID
        return response()->json($client); // Retourner les détails du client en JSON
    }

    /**
     * Afficher les abonnements d'un client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscriptions($id)
    {
        $client = Client::findOrFail($id); // Trouver le client
        $subscriptions = $client->subscriptions; // Récupérer tous les abonnements du client
        return response()->json($subscriptions); // Retourner les abonnements en JSON
    }

    /**
     * Afficher les commandes d'un client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orders($id)
    {
        $client = Client::findOrFail($id); // Trouver le client
        $orders = $client->orders; // Récupérer toutes les commandes du client
        return response()->json($orders); // Retourner les commandes en JSON
    }

    /**
     * Créer un nouveau client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $client = Client::create($request->all()); // Créer un client avec les données fournies
        return response()->json($client, 201); // Retourner le client créé en JSON avec un code de réponse 201 (créé)
    }

    /**
     * Mettre à jour les informations d'un client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all()); // Mettre à jour les informations du client

        return response()->json($client); // Retourner le client mis à jour en JSON
    }

    /**
     * Supprimer un client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id); // Trouver le client par ID
        $client->delete(); // Supprimer le client

        return response()->json(null, 204); // Retourner une réponse vide avec un code 204 (suppression réussie)
    }
}
