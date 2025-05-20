<?php

// app/Models/Stock.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['aliment_id', 'quantite', 'date_entree'];  // Ces champs sont assignables en masse

    // Définir la relation avec le modèle Aliment (un stock appartient à un aliment)
    public function aliment()
    {
        return $this->belongsTo(Aliment::class);
    }
}
