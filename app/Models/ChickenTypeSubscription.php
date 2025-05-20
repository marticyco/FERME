<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChickenTypeSubscription extends Pivot
{
    protected $table = 'chicken_type_subscription'; // Le nom de la table pivot

    // Les colonnes que tu souhaites rendre accessibles pour une manipulation plus facile
    protected $fillable = [
        'subscription_id',
        'chicken_type_id',
        'quantity',
    ];

    // Si tu veux personnaliser l'ID de la clé primaire, tu peux le faire ici
    protected $primaryKey = 'id';
}
