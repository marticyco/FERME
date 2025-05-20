<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PonteController extends Controller
{


    /**
     * Afficher la page de création d'un lot.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retourne la vue pour créer un nouveau lot
        return view('Ponte.index');
    }
    
}
