<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'client_id',
        'description',
        'start_date',
        'end_date',
        'subscription_type',
        'delivery_address',
        'price',
    ];

    // Relation avec Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation many-to-many avec ChickenType
    public function chickenTypes()
    {
        return $this->belongsToMany(ChickenType::class, 'chicken_type_subscription')
                    ->withPivot('quantity'); // Quantité dans la table pivot
    }

   // Subscription.php (modèle Eloquent)
public function calculateSubscriptionPrice()
{
    $total = 0;

    foreach ($this->chickenTypes as $chickenType) {
        $quantity = $chickenType->pivot->quantity ?? 1;
        $total += $chickenType->price * $quantity;
    }

    return $total;
}



    /**
     * Get the duration of the subscription in days.
     * Calculer la durée en jours entre la date de début et la date de fin de l'abonnement.
     */
    public function getDurationInDays()
    {
        $startDate = strtotime($this->start_date); // Convertir la date de début en timestamp
        $endDate = strtotime($this->end_date); // Convertir la date de fin en timestamp

        return ($endDate - $startDate) / 86400; // Calculer la différence en jours (86400 secondes dans un jour)
    }

    /**
     * Get the duration of the subscription in months.
     * Calculer la durée en mois sans utiliser Carbon.
     */
    public function getDurationInMonths()
    {
        $startDate = strtotime($this->start_date); // Convertir la date de début en timestamp
        $endDate = strtotime($this->end_date); // Convertir la date de fin en timestamp

        // Calculer la différence en mois
        $startMonth = date('m', $startDate);
        $endMonth = date('m', $endDate);
        $startYear = date('Y', $startDate);
        $endYear = date('Y', $endDate);

        return (($endYear - $startYear) * 12) + ($endMonth - $startMonth); // Différence en mois
    }

    /**
     * Get the duration of the subscription in years.
     * Calculer la durée en années sans utiliser Carbon.
     */
    public function getDurationInYears()
    {
        $startDate = strtotime($this->start_date); // Convertir la date de début en timestamp
        $endDate = strtotime($this->end_date); // Convertir la date de fin en timestamp

        // Calculer la différence en années
        $startYear = date('Y', $startDate);
        $endYear = date('Y', $endDate);

        return $endYear - $startYear; // Différence en années
    }
}
