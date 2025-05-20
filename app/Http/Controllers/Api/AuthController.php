<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller; // Ajouter cette ligne pour importer le Controller de base
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
{
    // Validation des données
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:191',
        'email' => 'required|string|email|max:191|unique:users',
        'password' => 'required|string|confirmed|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Création de l'utilisateur
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Création d'un token pour l'utilisateur
    $token = $user->createToken('MyApp')->plainTextToken;

    // Retourner le token d'authentification
    return response()->json([
        'token' => $token,
        'user' => $user,  // Optionnel: retourner aussi les informations de l'utilisateur
    ]);
}  


                       
                          // Méthode pour obtenir la liste des utilisateurs
    public function getUsers()
    {
        // Récupérer tous les utilisateurs
        $users = User::all(); 

        return response()->json($users);
    }

    // Connexion d'un utilisateur
    public function login(Request $request)
    {
        // Récupérer les informations d'authentification
        $credentials = $request->only('email', 'password');

        // Vérifier les informations d'identification
        if (Auth::attempt($credentials)) {
            // Récupérer l'utilisateur connecté
            $user = Auth::user();
            // Créer un token d'authentification
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        // Retourner un message d'erreur si l'authentification échoue
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Déconnexion de l'utilisateur
    public function logout(Request $request)
    {
        // Supprimer tous les tokens de l'utilisateur actuel
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Récupérer les informations de l'utilisateur connecté
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
