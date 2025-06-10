<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParametresController extends Controller
{
    /**
     * Affiche la page des paramètres.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('Parametres.index'); // => ressources/views/Parametres/index.blade.php
    }
}
