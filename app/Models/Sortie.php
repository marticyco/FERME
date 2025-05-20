<?php

// app/Models/Sortie.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sortie extends Model
{
    use HasFactory;

    protected $fillable = ['aliment_id', 'quantite', 'date_sortie'];  // Ajoutez 'date_sortie' ici

    // Définir la relation avec le modèle Aliment (une sortie appartient à un aliment)
    public function aliment()
    {
        return $this->belongsTo(Aliment::class);
    }
}
