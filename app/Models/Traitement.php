<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traitement extends Model
{
    use HasFactory;

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'band_id',           // ID de la bande associée
        'nom',               // Nom du traitement
        'description',       // Description du traitement
        'date_planifiee',    // Date prévue pour le traitement
        'prix',              // Prix du traitement
        'recu_image_path',   // Chemin vers l'image du reçu
    ];

    /**
     * Relation avec le modèle Band.
     * Chaque traitement appartient à une bande.
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }
}
