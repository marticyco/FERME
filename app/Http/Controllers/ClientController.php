<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Méthode pour afficher la vue des clients
    public function index()
    {
        // Retourner simplement la vue
        return view('Client.index'); // Nom de la vue ici
    }
}
