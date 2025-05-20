<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\Band;
use App\Models\ExpenseCategory;  // Nouveau modèle pour les catégories de dépenses
use App\Models\SaleCategory;     // Nouveau modèle pour les catégories de ventes
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class FinanceController extends Controller
{
    // 🎯 Récupérer les finances globales ou par bande
    public function getFinances(Request $request, $bandCode = null)
    {
        try {
            $band = $bandCode ? Band::where('code', $bandCode)->firstOrFail() : null;
    
            $from = $request->query('from');
            $to = $request->query('to');
    
            // Préparation des requêtes avec ou sans bande
            $expenseQuery = $band
                ? $band->expenses()
                : Expense::whereNull('band_id');
    
            $saleQuery = $band
                ? $band->sales()
                : Sale::whereNull('band_id');
    
            // Ajout des filtres de date si fournis
            if ($from) {
                $expenseQuery->where('date', '>=', $from);
                $saleQuery->where('date', '>=', $from);
            }
    
            if ($to) {
                $expenseQuery->where('date', '<=', $to);
                $saleQuery->where('date', '<=', $to);
            }
    
            // Récupération finale des données
            $expenses = $expenseQuery->orderBy('date')->get();
            $sales = $saleQuery->orderBy('date')->get();
    
            // Formatage des dates
            $expenses->each(fn ($e) => $e->date = Carbon::parse($e->date)->toDateString());
            $sales->each(fn ($s) => $s->date = Carbon::parse($s->date)->toDateString());
    
            // Calculs
            $totalExpenses = $expenses->sum('montant');
            $totalSales = $sales->sum('montant');
            $cashFlow = $totalSales - $totalExpenses;
    
            return response()->json([
                'expenses' => $expenses,
                'sales' => $sales,
                'cashFlow' => $cashFlow,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des finances',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // 🎯 Ajouter une dépense
    public function addExpense(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'band_id' => 'nullable|exists:bands,id', // Accepte un band_id ou null
            'category_id' => 'nullable|exists:expense_categories,id', // Nouvelle validation pour catégorie
        ]);

        $expense = Expense::create([
            'band_id' => $request->band_id,
            'montant' => $request->montant,
            'description' => $request->description,
            'date' => $request->date,
            'category_id' => $request->category_id,  // Enregistrer la catégorie
        ]);

        return response()->json($expense, 201);
    }

    // 🎯 Ajouter une vente
    public function addSale(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'band_id' => 'nullable|exists:bands,id',
            'category_id' => 'nullable|exists:sale_categories,id', // Nouvelle validation pour catégorie
        ]);

        $sale = Sale::create([
            'band_id' => $request->band_id,
            'montant' => $request->montant,
            'description' => $request->description,
            'date' => $request->date,
            'category_id' => $request->category_id,  // Enregistrer la catégorie
        ]);

        return response()->json($sale, 201);
    }

    // 🎯 Mettre à jour une dépense
    public function updateExpense(Request $request, $id, $bandCode = null)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:expense_categories,id', // Nouvelle validation pour catégorie
        ]);

        $expense = Expense::findOrFail($id);

        $band = $bandCode ? Band::where('code', $bandCode)->firstOrFail() : null;

        $expense->update([
            'band_id' => $band?->id,
            'montant' => $request->montant,
            'description' => $request->description,
            'date' => $request->date,
            'category_id' => $request->category_id,  // Mise à jour de la catégorie
        ]);

        return response()->json($expense);
    }

    // 🎯 Mettre à jour une vente
    public function updateSale(Request $request, $id, $bandCode = null)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:sale_categories,id', // Nouvelle validation pour catégorie
        ]);

        $sale = Sale::findOrFail($id);

        $band = $bandCode ? Band::where('code', $bandCode)->firstOrFail() : null;

        $sale->update([
            'band_id' => $band?->id,
            'montant' => $request->montant,
            'description' => $request->description,
            'date' => $request->date,
            'category_id' => $request->category_id,  // Mise à jour de la catégorie
        ]);

        return response()->json($sale);
    }

    // 🎯 Supprimer une dépense
    public function deleteExpense($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Dépense supprimée']);
    }

    // 🎯 Supprimer une vente
    public function deleteSale($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return response()->json(['message' => 'Vente supprimée']);
    }

    // 🎯 Récupérer toutes les catégories de dépenses
    public function getExpenseCategories()
    {
        $categories = ExpenseCategory::all();
        return response()->json($categories);
    }

    // 🎯 Récupérer toutes les catégories de ventes
    public function getSaleCategories()
    {
        $categories = SaleCategory::all();
        return response()->json($categories);
    }

    // 🎯 Ajouter une catégorie de dépense
    public function addExpenseCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = ExpenseCategory::create([
            'name' => $request->name,
        ]);

        return response()->json($category, 201);
    }

    // 🎯 Ajouter une catégorie de vente
    public function addSaleCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = SaleCategory::create([
            'name' => $request->name,
        ]);

        return response()->json($category, 201);
    }


    // 🎯 Supprimer une catégorie de dépense
    public function deleteExpenseCategory($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Catégorie de dépense supprimée']);
    }

    // 🎯 Supprimer une catégorie de vente
    public function deleteSaleCategory($id)
    {
        $category = SaleCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Catégorie de vente supprimée']);
    }


    
    public function getMonthlyFinances(Request $request)
    {
        try {
            // Requêtes avec une sous-requête pour ordonner proprement
            $sales = DB::table('sales')
                ->select(
                    DB::raw('DATE_FORMAT(date, "%b %Y") as month'),
                    DB::raw('DATE_FORMAT(date, "%Y-%m") as sort_month'),
                    DB::raw('SUM(montant) as montant')
                )
                ->groupBy(DB::raw('DATE_FORMAT(date, "%b %Y")'), DB::raw('DATE_FORMAT(date, "%Y-%m")'))
                ->orderBy('sort_month')
                ->get();
    
            $expenses = DB::table('expenses')
                ->select(
                    DB::raw('DATE_FORMAT(date, "%b %Y") as month'),
                    DB::raw('DATE_FORMAT(date, "%Y-%m") as sort_month'),
                    DB::raw('SUM(montant) as montant')
                )
                ->groupBy(DB::raw('DATE_FORMAT(date, "%b %Y")'), DB::raw('DATE_FORMAT(date, "%Y-%m")'))
                ->orderBy('sort_month')
                ->get();
    
            // Fusionner les mois
            $months = collect($sales)->pluck('month')
                ->merge($expenses->pluck('month'))
                ->unique()
                ->sortBy(function ($month) {
                    return \DateTime::createFromFormat('M Y', $month)->format('Y-m');
                })->values();
    
            $result = [
                'sales' => [],
                'expenses' => [],
                'cashFlow' => []
            ];
    
            foreach ($months as $month) {
                $sale = $sales->firstWhere('month', $month)?->montant ?? 0;
                $expense = $expenses->firstWhere('month', $month)?->montant ?? 0;
    
                $result['sales'][] = ['date' => $month, 'montant' => (float) $sale];
                $result['expenses'][] = ['date' => $month, 'montant' => (float) $expense];
                $result['cashFlow'][] = ['date' => $month, 'montant' => (float) ($sale - $expense)];
            }
    
            return response()->json($result);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du calcul des finances mensuelles',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
