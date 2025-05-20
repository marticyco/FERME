<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlimentController extends Controller
{
    /**
     * Afficher la page 
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('Aliments.index');  
    }
}

