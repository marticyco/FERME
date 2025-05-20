<?php

// app/Models/Entree.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    use HasFactory;

    protected $fillable = ['aliment_id', 'fournisseur_id', 'quantite','date_entree' ];  // Ces champs sont assignables en masse

    // Définir la relation avec le modèle Aliment (une entrée appartient à un aliment)
    public function aliment()
    {
        return $this->belongsTo(Aliment::class);
    }

    // Définir la relation avec le modèle Fournisseur (une entrée est associée à un fournisseur)
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}
