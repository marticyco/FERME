<?php

// app/Models/Aliment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aliment extends Model
{
    use HasFactory;

    // Spécification des attributs assignables en masse
    protected $fillable = ['nom', 'type', 'prix_unitaire'];

    /**
     * Définir la relation avec le modèle Stock.
     * Un aliment a un seul stock (si chaque aliment a un seul stock).
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);  // Chaque aliment a un seul stock
    }

    /**
     * Définir la relation avec les entrées.
     * Un aliment peut avoir plusieurs entrées.
     */
    public function entrees()
    {
        return $this->hasMany(Entree::class);  // Chaque aliment peut avoir plusieurs entrées
    }

    /**
     * Définir la relation avec les sorties.
     * Un aliment peut avoir plusieurs sorties.
     */
    public function sorties()
    {
        return $this->hasMany(Sortie::class);  // Chaque aliment peut avoir plusieurs sorties
    }
}
