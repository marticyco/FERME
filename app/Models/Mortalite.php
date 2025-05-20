<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mortalite extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_id',
        'nombre_morts',
        'date_mortalite',
        'nombre_males_morts',
        'nombre_femelles_morts',
    ];

    // Relation avec Lot
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    // Event pour calculer le nombre de morts avant l'enregistrement
    protected static function booted()
    {
        static::saving(function ($mortalite) {
            // Calculer le nombre de morts total
            $mortalite->nombre_morts = $mortalite->nombre_males_morts + $mortalite->nombre_femelles_morts;
        });

        static::created(function ($mortalite) {
            // Récupérer le lot concerné
            $lot = $mortalite->lot;

            // Calculer les nouveaux nombres de mâles et femelles restants
            $nouveau_nombre_males = $lot->nombre_males - $mortalite->nombre_males_morts;
            $nouveau_nombre_femelles = $lot->nombre_femelles - $mortalite->nombre_femelles_morts;

            // Mettre à jour les champs du lot
            $lot->update([
                'nombre_males' => $nouveau_nombre_males,
                'nombre_femelles' => $nouveau_nombre_femelles,
            ]);
        });
    }
}
