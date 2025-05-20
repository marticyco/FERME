<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Models\Client;
use App\Models\ChickenType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Notifications\SubscriptionCreated;




class SubscriptionController extends Controller
{
    /**
     * Afficher tous les abonnements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer tous les abonnements avec leurs clients et types de poulet associés
        $subscriptions = Subscription::with(['client', 'chickenTypes'])->get();
        return response()->json($subscriptions);
    }

    /**
     * Afficher les détails d'un abonnement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Trouver un abonnement avec son client et ses types de poulet associés
        $subscription = Subscription::with(['client', 'chickenTypes'])->findOrFail($id);
        return response()->json($subscription);
    }

    /**
     * Créer un nouvel abonnement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'chicken_types' => 'required|array',
        'chicken_types.*.id' => 'required|exists:chicken_types,id',
        'chicken_types.*.quantity' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'subscription_type' => 'required|in:weekly,monthly,bi-monthly,quarterly,semi-annual',
        'delivery_address' => 'required|string|max:255',
    ]);

    // 💡 Calculer le prix total AVANT la création
    $price = 0;
    foreach ($request->chicken_types as $type) {
        $chicken = \App\Models\ChickenType::find($type['id']);
        if ($chicken) {
            $price += $chicken->price * $type['quantity'];
        }
    }

    // ✅ Créer l'abonnement avec le prix calculé
    $subscription = Subscription::create([
        'client_id' => $request->client_id,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'subscription_type' => $request->subscription_type,
        'delivery_address' => $request->delivery_address,
        'price' => $price,
    ]);

    // 🔗 Associer les types de poulets avec leurs quantités
    foreach ($request->chicken_types as $type) {
        $subscription->chickenTypes()->attach($type['id'], ['quantity' => $type['quantity']]);
    }

    // Charger les relations utiles
    $subscription->load(['client', 'chickenTypes']);

    // ✅ Tenter d'envoyer la notification par email
    try {
        if ($subscription->client && $subscription->client->email) {
            $subscription->client->notify(new SubscriptionCreated($subscription));
        } else {
            Log::warning("Le client avec l'ID {$subscription->client_id} n'a pas d'adresse email.");
        }
    } catch (\Exception $e) {
        Log::error("Erreur lors de la notification du client : " . $e->getMessage());
    }

    return response()->json($subscription, 201);
}


    /**
     * Mettre à jour un abonnement existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'chicken_types' => 'required|array',
        'chicken_types.*.id' => 'required|exists:chicken_types,id',
        'chicken_types.*.quantity' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'subscription_type' => 'required|in:weekly,monthly,bi-monthly,quarterly,semi-annual',
        'delivery_address' => 'required|string|max:255',
    ]);

    $subscription = Subscription::findOrFail($id);

    $subscription->update([
        'client_id' => $request->client_id,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'subscription_type' => $request->subscription_type,
        'delivery_address' => $request->delivery_address,
    ]);

    // Supprimer les anciens types de poulet et en ajouter des nouveaux
    $subscription->chickenTypes()->detach();

    foreach ($request->chicken_types as $type) {
        $subscription->chickenTypes()->attach($type['id'], ['quantity' => $type['quantity']]);
    }

    $subscription->load('chickenTypes');

    $subscription->price = $subscription->calculateSubscriptionPrice();
    $subscription->save();

    $subscription->load(['client', 'chickenTypes']);

    return response()->json($subscription, 200);
}

    /**
     * Supprimer un abonnement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Trouver l'abonnement à supprimer
        $subscription = Subscription::findOrFail($id);

        // Supprimer l'abonnement
        $subscription->delete();

        return response()->json(null, 204); // Retourner une réponse vide avec le code 204 (suppression réussie)
    }

    /**
     * Calculer la durée d'un abonnement en jours.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDurationInDays($id)
    {
        // Trouver l'abonnement
        $subscription = Subscription::findOrFail($id);

        // Récupérer la durée en jours
        $durationInDays = $subscription->getDurationInDays();
        return response()->json(['duration_in_days' => $durationInDays]);
    }

    /**
     * Calculer la durée d'un abonnement en mois.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDurationInMonths($id)
    {
        // Trouver l'abonnement
        $subscription = Subscription::findOrFail($id);

        // Récupérer la durée en mois
        $durationInMonths = $subscription->getDurationInMonths();
        return response()->json(['duration_in_months' => $durationInMonths]);
    }

    /**
     * Calculer la durée d'un abonnement en années.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDurationInYears($id)
    {
        // Trouver l'abonnement
        $subscription = Subscription::findOrFail($id);

        // Récupérer la durée en années
        $durationInYears = $subscription->getDurationInYears();
        return response()->json(['duration_in_years' => $durationInYears]);
    }

  public function getMonthlySubscriptionStats()
{
    $now = now();
    $stats = Subscription::whereYear('start_date', $now->year)
        ->whereMonth('start_date', $now->month)
        ->selectRaw('COUNT(*) as total_subscriptions, SUM(price) as total_revenue')
        ->first();

    return response()->json($stats);
}


}
