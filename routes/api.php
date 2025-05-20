<?php

use App\Http\Controllers\Api\IncubationController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/incubations')->middleware('auth:sanctum')->group(function () {

    Route::get('/', [IncubationController::class, 'index'])
        ->middleware('can:view incubations');

    Route::get('alerts', [IncubationController::class, 'checkAlerts'])
        ->middleware('can:view incubations');

    Route::get('{id}', [IncubationController::class, 'show'])
        ->middleware('can:view incubation');

    Route::post('/', [IncubationController::class, 'store'])
        ->middleware('can:create incubation');

    Route::put('{id}', [IncubationController::class, 'update'])
        ->middleware('can:update incubation');

    Route::patch('{id}', [IncubationController::class, 'updateFertiles'])
        ->middleware('can:update incubation');

    Route::put('{id}', [IncubationController::class, 'updateEclosion'])
        ->middleware('can:update incubation');

    Route::delete('{id}', [IncubationController::class, 'destroy'])
        ->middleware('can:delete incubation');
});


use App\Http\Controllers\Api\AuthController;
Route::prefix('api')->group(function () {
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
Route::middleware('auth:sanctum')->get('/users', [AuthController::class, 'getUsers']); // Si tu veux protéger la liste des utilisateurs
});



use App\Http\Controllers\Api\LotController;
use App\Http\Controllers\Api\PonteController;


// Protéger les routes avec Sanctum (middleware auth:sanctum)
Route::middleware('auth:sanctum')->prefix('api/lots')->group(function () {

    // Routes pour la gestion des lots
    Route::get('/', [LotController::class, 'index']) // Lister tous les lots
        ->middleware('can:view lot');

    Route::get('{id}', [LotController::class, 'showLot']) // Récupérer un lot spécifique
        ->middleware('can:view lot');

    Route::post('/', [LotController::class, 'storeLot']) // Créer un nouveau lot
        ->middleware('can:create lot');

    Route::put('{id}', [LotController::class, 'update']) // Mettre à jour un lot
        ->middleware('can:update lot');

    Route::delete('{id}', [LotController::class, 'destroy']) // Supprimer un lot
        ->middleware('can:delete lot');

    Route::get('/performance/{lotId}', [LotController::class, 'getLotPerformance']) // Récupérer les performances du lot
        ->middleware('can:view performance');

    // Routes pour la gestion de la mortalité
    Route::post('/{lotId}/mortalite', [PonteController::class, 'storeMortalite']) // Enregistrer une mortalité
        ->middleware('can:create mortalite');

    Route::get('/{lotId}/mortalite/stats', [PonteController::class, 'showMortaliteStats']) // Récupérer les stats de mortalité pour un lot spécifique
        ->middleware('can:view mortalite stats');

    Route::get('/mortalite/stats', [PonteController::class, 'showMortaliteStats']) // Récupérer les stats de mortalité globales
        ->middleware('can:view global mortalite stats');

    // Routes pour la gestion des pontes
    Route::post('/{lotId}/ponte', [PonteController::class, 'storePonte']) // Enregistrer une ponte
        ->middleware('can:create ponte');

    Route::get('/{lotId}/ponte', [PonteController::class, 'getPontesByLot']) // Récupérer les pontes d'un lot spécifique
        ->middleware('can:view ponte');

    // Route pour récupérer les données de la vue d'ensemble du lot
    Route::get('/{lotId}/overview', [PonteController::class, 'getOverviewData']) // Vue d'ensemble du lot
        ->middleware('can:view overview');
});

// Route protégée hors du groupe "lots"
Route::middleware('auth:sanctum')->prefix('api')->group(function () {
    Route::get('/total-oeufs', [PonteController::class, 'getTotalOeufs']) // Récupérer le total d'œufs
        ->middleware('can:view ponte');
});



// Routes publiques ou non protégées avec Sanctum
Route::prefix('api')->group(function () {
    // Routes publiques comme l'affichage des lots sans authentification
    Route::get('lots', [LotController::class, 'index']); // Lister tous les lots
    Route::get('lots/{id}', [LotController::class, 'showLot']); // Récupérer un lot spécifique
});



use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\ChickenTypeController;


// Routes liées aux clients
Route::middleware('auth:sanctum')->prefix('api/')->group(function () {
    // Routes pour gérer les clients
    Route::get('clients', [ClientController::class, 'index']);          // Liste des clients
    Route::get('clients/{id}', [ClientController::class, 'show']);      // Détails d'un client spécifique
    Route::post('clients', [ClientController::class, 'store']);         // Créer un client
    Route::put('clients/{id}', [ClientController::class, 'update']);    // Mettre à jour un client existant
    Route::delete('clients/{id}', [ClientController::class, 'destroy']); // Supprimer un client

    // Routes pour les commandes
    Route::get('orders', [OrderController::class, 'index']);           // Liste des commandes
    Route::get('orders/{id}', [OrderController::class, 'show']);       // Détails d'une commande
    Route::post('orders', [OrderController::class, 'store']);          // Créer une commande
    Route::put('orders/{id}', [OrderController::class, 'update']);     // Mettre à jour une commande
    Route::delete('orders/{id}', [OrderController::class, 'destroy']); // Supprimer une commande
    Route::get('orders/{id}/total-price', [OrderController::class, 'calculateTotalPrice']); // Calculer le prix d'une commande
    Route::get('/orders/monthly-stats', [OrderController::class, 'getMonthlyStats']);

    // Routes pour les abonnements
    Route::get('subscriptions', [SubscriptionController::class, 'index']);  // Liste des abonnements
    Route::get('subscriptions/{id}', [SubscriptionController::class, 'show']); // Détails d'un abonnement spécifique
    Route::post('subscriptions', [SubscriptionController::class, 'store']); // Créer un abonnement
    Route::put('subscriptions/{id}', [SubscriptionController::class, 'update']); // Mettre à jour un abonnement
    Route::delete('subscriptions/{id}', [SubscriptionController::class, 'destroy']); // Supprimer un abonnement
    Route::get('/subscriptions/monthly-stats', [SubscriptionController::class, 'getMonthlySubscriptionStats']);


     // Récupérer tous les types de poulet
     Route::get('chicken-types', [ChickenTypeController::class, 'index']);
    
     // Récupérer un type de poulet par ID
     Route::get('chicken-types/{id}', [ChickenTypeController::class, 'show']);
     
     // Créer un nouveau type de poulet
     Route::post('chicken-types', [ChickenTypeController::class, 'store']);
     
     // Mettre à jour un type de poulet
     Route::put('chicken-types/{id}', [ChickenTypeController::class, 'update']);
     
     // Supprimer un type de poulet
     Route::delete('chicken-types/{id}', [ChickenTypeController::class, 'destroy']);
     
     // Calculer le prix en fonction du poids et du type
     Route::get('chicken-types/{id}/calculate-price/{type}/{weight}', [ChickenTypeController::class, 'calculatePrice']);
 });



 use App\Http\Controllers\Api\AlimentController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\EntreeController;
use App\Http\Controllers\Api\SortieController;
use App\Http\Controllers\Api\FournisseurController;

Route::middleware('auth:sanctum')->prefix('api')->group(function () {

    // Routes pour la gestion des aliments
    Route::prefix('aliments')->group(function () {
        Route::get('/', [AlimentController::class, 'index']);           // Liste des aliments
        Route::get('{id}', [AlimentController::class, 'show']);        // Afficher un aliment spécifique
        Route::post('/', [AlimentController::class, 'store']);         // Créer un nouvel aliment
        Route::put('{id}', [AlimentController::class, 'update']);      // Mettre à jour un aliment
        Route::delete('{id}', [AlimentController::class, 'destroy']);  // Supprimer un aliment
    });

    // Routes pour la gestion des stocks
    Route::prefix('stocks')->group(function () {
        Route::get('/', [StockController::class, 'index']);           // Liste des stocks
        Route::get('{id}', [StockController::class, 'show']);        // Afficher un stock spécifique
        Route::post('/', [StockController::class, 'store']);         // Créer un nouveau stock
        Route::put('{id}', [StockController::class, 'update']);      // Mettre à jour un stock
        Route::delete('{id}', [StockController::class, 'destroy']);  // Supprimer un stock
        Route::get('/low', [StockController::class, 'lowStock']);
        

        // Mettre à jour le stock après une entrée
        Route::post('update-after-entree/{aliment_id}/{quantite}', [StockController::class, 'updateAfterEntree']); // Mettre à jour après une entrée

        // Mettre à jour le stock après une sortie
        Route::post('update-after-sortie/{aliment_id}/{quantite}', [StockController::class, 'updateAfterSortie']); // Mettre à jour après une sortie
    });

    // Routes pour la gestion des entrées
    Route::prefix('entrees')->group(function () {
        Route::get('/', [EntreeController::class, 'index']);           // Liste des entrées
        Route::get('{id}', [EntreeController::class, 'show']);        // Afficher une entrée spécifique
        Route::post('/', [EntreeController::class, 'store']);         // Créer une nouvelle entrée
        Route::put('{id}', [EntreeController::class, 'update']);      // Mettre à jour une entrée
        Route::delete('{id}', [EntreeController::class, 'destroy']);  // Supprimer une entrée
    });

    // Routes pour la gestion des sorties
    Route::prefix('sorties')->group(function () {
        Route::get('/', [SortieController::class, 'index']);           // Liste des sorties
        Route::get('{id}', [SortieController::class, 'show']);        // Afficher une sortie spécifique
        Route::post('/', [SortieController::class, 'store']);         // Créer une nouvelle sortie
        Route::put('{id}', [SortieController::class, 'update']);      // Mettre à jour une sortie
        Route::delete('{id}', [SortieController::class, 'destroy']);  // Supprimer une sortie
    });

    // Routes pour la gestion des fournisseurs
    Route::prefix('fournisseurs')->group(function () {
        Route::get('/', [FournisseurController::class, 'index']);           // Liste des fournisseurs
        Route::get('{id}', [FournisseurController::class, 'show']);        // Afficher un fournisseur spécifique
        Route::post('/', [FournisseurController::class, 'store']);         // Créer un nouveau fournisseur
        Route::put('{id}', [FournisseurController::class, 'update']);      // Mettre à jour un fournisseur
        Route::delete('{id}', [FournisseurController::class, 'destroy']);  // Supprimer un fournisseur
    });

});



use App\Http\Controllers\Api\BandController;
use App\Http\Controllers\Api\DailyMortalityController;
use App\Http\Controllers\Api\DailyFeedConsumptionController;
use App\Http\Controllers\Api\TraitementController;
use App\Http\Controllers\Api\FinanceController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\JournalEntryController;


Route::prefix('api/')->middleware('auth:sanctum')->group(function () {

    // Routes pour les bandes
    Route::prefix('bands')->group(function () {
        Route::get('/', [BandController::class, 'index']); // Liste des bandes
        Route::post('/', [BandController::class, 'store']); // Créer une nouvelle bande
        Route::get('{bandId}', [BandController::class, 'show']); // Afficher une bande spécifique
        Route::put('{bandId}', [BandController::class, 'update']); // Mettre à jour une bande
        Route::delete('{bandId}', [BandController::class, 'destroy']); // Supprimer une bande
        Route::get('/count/month', [BandController::class, 'countBandsStartedThisMonth']);
        Route::get('/list/month', [BandController::class, 'listBandsThisMonth']);

    });

    // Routes pour les mortalités d'une bande spécifique
    Route::prefix('bands/{bandId}/mortalities')->group(function () {
        Route::get('/', [DailyMortalityController::class, 'index']); // Liste des mortalités pour une bande
        Route::post('/', [DailyMortalityController::class, 'store']); // Ajouter une mortalité
        Route::get('{id}', [DailyMortalityController::class, 'show']); // Afficher une mortalité spécifique
        Route::put('{id}', [DailyMortalityController::class, 'update']); // Mettre à jour une mortalité
        Route::delete('{id}', [DailyMortalityController::class, 'destroy']); // Supprimer une mortalité
    });


    
    // Routes pour les consommations d'aliments d'une bande spécifique
    Route::prefix('bands/{bandId}/feed-consumptions')->group(function () {
        Route::get('/', [DailyFeedConsumptionController::class, 'index']); // Liste des consommations d'aliments pour une bande
        Route::post('/', [DailyFeedConsumptionController::class, 'store']); // Ajouter une consommation d'aliments
        Route::get('{id}', [DailyFeedConsumptionController::class, 'show']); // Afficher une consommation spécifique
        Route::put('{id}', [DailyFeedConsumptionController::class, 'update']); // Mettre à jour une consommation d'aliments
        Route::delete('{id}', [DailyFeedConsumptionController::class, 'destroy']); // Supprimer une consommation d'aliments
    });

    // Routes pour les traitements d'une bande spécifique
    Route::prefix('bands/{bandCode}/traitements')->group(function () {
        // Liste des traitements pour une bande spécifique
        Route::get('/', [TraitementController::class, 'indexForBand']);

        // Créer un nouveau traitement pour une bande spécifique
        Route::post('/', [TraitementController::class, 'storeForBand']);
        
        // Afficher les détails d'un traitement spécifique
        Route::get('{traitement}', [TraitementController::class, 'show']);

        // Mettre à jour un traitement spécifique
        Route::put('{traitement}', [TraitementController::class, 'update']);
        
        // Supprimer un traitement spécifique
        Route::delete('{traitement}', [TraitementController::class, 'destroy']);
    });
   

     // 🎯 Routes pour les finances PAR bande
     Route::prefix('bands/{bandCode}')->group(function () {
        Route::get('/finances', [FinanceController::class, 'getFinances']);
        Route::post('/expenses', [FinanceController::class, 'addExpense']);
        Route::post('/sales', [FinanceController::class, 'addSale']);
        Route::put('/expenses/{expenseId}', [FinanceController::class, 'updateExpense']);
        Route::put('/sales/{saleId}', [FinanceController::class, 'updateSale']);
        Route::delete('/expenses/{expenseId}', [FinanceController::class, 'deleteExpense']);
        Route::delete('/sales/{saleId}', [FinanceController::class, 'deleteSale']);
    });

    // 🌍 Routes pour les finances GLOBALES
    Route::get('/finances', [FinanceController::class, 'getFinances']);
    Route::get('/finances-cash', [FinanceController::class, 'getMonthlyFinances']);

    Route::post('/expenses', [FinanceController::class, 'addExpense']);
    Route::post('/sales', [FinanceController::class, 'addSale']);
    Route::put('/expenses/{expenseId}', [FinanceController::class, 'updateExpense']);
    Route::put('/sales/{saleId}', [FinanceController::class, 'updateSale']);
    Route::delete('/expenses/{expenseId}', [FinanceController::class, 'deleteExpense']);
    Route::delete('/sales/{saleId}', [FinanceController::class, 'deleteSale']);



    // 🗂 Catégories (même contrôleur pour les deux types)
Route::get('/categories/expenses', [FinanceController::class, 'getExpenseCategories']);
Route::post('/categories/expenses', [FinanceController::class, 'addExpenseCategory']);
Route::put('/categories/expenses/{id}', [FinanceController::class, 'updateExpenseCategory']);
Route::delete('/categories/expenses/{id}', [FinanceController::class, 'deleteExpenseCategory']);

Route::get('/categories/sales', [FinanceController::class, 'getSaleCategories']);
Route::post('/categories/sales', [FinanceController::class, 'addSaleCategory']);
Route::put('/categories/sales/{id}', [FinanceController::class, 'updateSaleCategory']);
Route::delete('/categories/sales/{id}', [FinanceController::class, 'deleteSaleCategory']);





// Routes pour les comptes (Account)
Route::get('accounts', [AccountController::class, 'index']); // Lister tous les comptes
Route::post('accounts', [AccountController::class, 'store']); // Créer un nouveau compte
Route::get('accounts/{account}', [AccountController::class, 'show']); // Afficher un compte spécifique
Route::put('accounts/{account}', [AccountController::class, 'update']); // Mettre à jour un compte spécifique
Route::delete('accounts/{account}', [AccountController::class, 'destroy']); // Supprimer un compte spécifique

// Routes pour les écritures de journal (JournalEntry)
Route::get('journal-entries', [JournalEntryController::class, 'index']); // Lister toutes les écritures
Route::post('journal-entries', [JournalEntryController::class, 'store']); // Créer une nouvelle écriture
Route::get('journal-entries/{journalEntry}', [JournalEntryController::class, 'show']); // Afficher une écriture spécifique
Route::put('journal-entries/{journalEntry}', [JournalEntryController::class, 'update']); // Mettre à jour une écriture spécifique
Route::delete('journal-entries/{journalEntry}', [JournalEntryController::class, 'destroy']); // Supprimer une écriture spécifique


});



use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserController;

Route::prefix('api')->middleware('auth:sanctum')->group(function () {

    // Routes protégées par le rôle admin avec le préfixe 'api/admin'
    
    // Routes pour les rôles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

    // Routes pour les permissions
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/permissions', [PermissionController::class, 'store']);
    Route::get('/permissions/{id}', [PermissionController::class, 'show']);
    Route::put('/permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);



    // Toutes tes routes sous ce groupe seront protégées par l'authentification et le rôle admin
    Route::get('/users', [UserController::class, 'index']); // Liste des utilisateurs
    Route::post('/users', [UserController::class, 'store']); // Créer un utilisateur
    Route::get('/users/{id}', [UserController::class, 'show']); // Détails d’un utilisateur
    Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole']); // Assigner un rôle
    Route::post('/users/{id}/remove-role', [UserController::class, 'removeRole']); // Retirer un rôle
    Route::post('/users/{id}/give-permission', [UserController::class, 'givePermission']); // Ajouter une permission
    Route::post('/users/{id}/revoke-permission', [UserController::class, 'revokePermission']); // Retirer une permission
    Route::put('/users/{id}', [UserController::class, 'update']); // Mettre à jour un utilisateur
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
