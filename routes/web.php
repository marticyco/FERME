<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncubationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\PonteController;
use App\Http\Controllers\AlimentController;
use App\Http\Controllers\BandController;
use App\Http\Controllers\TraitementController;
use App\Http\Controllers\RolePermissionController;



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/incubation-manager', [IncubationController::class, 'index'])->name('incubations.index');



Route::get('/auth', [AuthController::class, 'index']);



Route::get('/lot-manager', [LotController::class, 'index'])->name('Lot.index');
Route::get('/ponte-manager', [PonteController::class, 'index'])->name('Ponte.index');


Route::get('/client-manager', [ClientController::class, 'index']);
Route::get('/aliment-manager', [AlimentController::class, 'index']);
Route::get('/band-manager', [BandController::class, 'index']);
Route::get('/traitement-manager', [TraitementController::class, 'index']);
Route::get('/cash-manager', [FinanceController::class, 'index']);
Route::get('/account', [ComptabiliteController::class, 'index']);
Route::get('/permission-manager', [RolePermissionController::class, 'index']);


require __DIR__.'/api.php';

