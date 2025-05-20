<?php

namespace App\Http\Controllers\Api;
use App\Models\Client;
use App\Models\Order;
use App\Models\ChickenType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['client', 'chickenTypes'])->get());
    }

    public function show($id)
    {
        $order = Order::with(['client', 'chickenTypes'])->findOrFail($id);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        Log::info('📥 Requête reçue pour création de commande', $request->all());
    
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'delivery_address' => 'required|string|max:255',
            'order_date' => 'required|date',
           'status' => 'nullable|in:pending,shipped,delivered', // modifié
            'chicken_types' => 'required|array',
            'chicken_types.*.id' => 'required|exists:chicken_types,id',
            'chicken_types.*.quantity' => 'required|integer|min:1',
            'delivery_type' => 'required|in:express,scheduled',
            'scheduled_time' => 'required_if:delivery_type,scheduled|nullable|date|after:now',
            // ❌ Suppression de la validation du champ weight
        ]);
    
        // 💡 Calculer le prix total AVANT la création
        $price = 0;
        foreach ($request->chicken_types as $type) {
            $chicken = \App\Models\ChickenType::find($type['id']);
            if ($chicken) {
                $price += $chicken->price * $type['quantity'];
            }
        }
    
        // ⏰ Déterminer l'heure prévue de livraison
        $scheduledTime = $request->delivery_type === 'express'
            ? now()->addHours(2)
            : $request->scheduled_time;
    
        // 📝 Créer la commande
        $order = Order::create([
            'client_id' => $request->client_id,
            'delivery_address' => $request->delivery_address,
            'order_date' => $request->order_date,
            'status' => $request->status ?? 'pending', // valeur par défaut
            'price' => $price,
            'delivery_type' => $request->delivery_type,
            'scheduled_time' => $scheduledTime,
        ]);
    
        // 🐔 Associer les types de poulets avec quantité + poids (facultatif)
        foreach ($request->chicken_types as $type) {
            $order->chickenTypes()->attach($type['id'], [
                'quantity' => $type['quantity'],
                // 'weight' => $type['weight'] ?? 1.0 // ✅ Défaut si non fourni
            ]);
        }
    
        // 🔁 Charger les relations utiles
        $order->load(['client', 'chickenTypes']);
    
        return response()->json($order, 201);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'delivery_address' => 'required|string|max:255',
            'order_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'chicken_types' => 'required|array',
            'chicken_types.*.id' => 'required|exists:chicken_types,id',
            'chicken_types.*.quantity' => 'required|integer|min:1',
           
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'client_id' => $request->client_id,
            'delivery_address' => $request->delivery_address,
            'order_date' => $request->order_date,
            'status' => $request->status,
        ]);

        // Détacher et rattacher les nouveaux
        $order->chickenTypes()->detach();

        $total = 0;
        foreach ($request->chicken_types as $item) {
            $type = ChickenType::find($item['id']);
            if ($type) {
                $total += $type->price * $item['quantity'];
                $order->chickenTypes()->attach($type->id, [
                    'quantity' => $item['quantity'],
                    'weight' => $item['weight'],
                ]);
            }
        }

        $order->price = $total;
        $order->save();

        return response()->json($order->load(['client', 'chickenTypes']), 200);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(null, 204);
    }

  public function getMonthlyStats()
{
    $now = now();
    $stats = Order::whereYear('order_date', $now->year)
        ->whereMonth('order_date', $now->month)
        ->selectRaw('COUNT(*) as total_orders, SUM(price) as total_revenue')
        ->first();

    return response()->json($stats);
}


}
