<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Afficher la page d'authentification.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('Auth.index');  
    }
}
