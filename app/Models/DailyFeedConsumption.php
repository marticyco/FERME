<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyFeedConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id', // Identifiant de la bande associée
        'date', // Date de consommation
        'quantite_aliment' // Quantité d'aliments consommée
    ];

    // Relation avec la bande
    public function band()
    {
        return $this->belongsTo(Band::class);
    }
}

 

