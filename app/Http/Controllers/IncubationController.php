<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncubationController extends Controller
{
    // Méthode pour afficher la vue
    public function index()
    {
        // Retourner simplement la vue
        return view('incubations.index'); // Nom de la vue ici
    }
}
