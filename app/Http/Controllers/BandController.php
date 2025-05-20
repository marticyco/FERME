<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BandController extends Controller
{
    /**
     * Afficher la page des Bandes .
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('Bands.index');  
    }
}

