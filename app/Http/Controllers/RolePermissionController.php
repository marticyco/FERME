<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Affiche la page de gestion des rôles et permissions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('Role.index');  
    }
}
