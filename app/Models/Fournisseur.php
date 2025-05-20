<?php

// app/Models/Fournisseur.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'contact'];  // Ces champs sont assignables en masse

    // Définir la relation avec les entrées (un fournisseur peut fournir plusieurs aliments)
    public function entrees()
    {
        return $this->hasMany(Entree::class);
    }
}
