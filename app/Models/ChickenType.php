<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChickenType extends Model
{
    use HasFactory;

    // Attributs remplissables
    protected $fillable = [
        'name',        // Le nom du type de poulet
        'description', // Une description du type de poulet
        'type',        // Nouveau champ type
        'weight',      // Nouveau champ weight
        'price',       // Champ pour le prix, ajouté pour le stockage dans la base de données
    ];

    /**
     * Relation avec les abonnements.
     * Un type de poulet peut être utilisé dans plusieurs abonnements.
     */
  // Définir la relation avec les abonnements (many-to-many)
  public function subscriptions()
  {
      return $this->belongsToMany(Subscription::class, 'chicken_type_subscription')
                  ->withPivot('quantity'); // Inclure la quantité depuis la table pivot
  }
  

    /**
 * Définir la relation many-to-many avec les commandes.
 */
public function orders()
{
    return $this->belongsToMany(Order::class, 'chicken_type_order')
                ->withPivot('quantity'); 
                
}

}
