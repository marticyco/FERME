<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Importation de Carbon pour la gestion des dates

class Band extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', // Code unique de la bande
        'type', // Type de poulet
        'compte_initial', // Compte initial
        'compte_actuel', // Compte actuel
        'date_debut', // Date de début
        'date_fin', // Date de fin (calculée automatiquement)
    ];

    // Relation avec les mortalités journalières
    public function mortalites()
    {
        return $this->hasMany(DailyMortality::class);
    }

    // Relation avec les consommations d'aliments
    public function feedConsumptions()
    {
        return $this->hasMany(DailyFeedConsumption::class);
    }

    // Méthode pour générer le code automatiquement et calculer la date de fin
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Génération automatique du code de bande
            $model->code = 'BAND-' . strtoupper(uniqid());

            // Calcul de la date de fin (10 semaines après la date de début)
            if ($model->date_debut) {
                $model->date_fin = Carbon::parse($model->date_debut)->addWeeks(10);
            }

            // Initialisation du compte actuel
            $model->compte_actuel = $model->compte_initial; // Initialiser compte_actuel à compte_initial
        });

        //static::updating(function ($model) {
            // Mettre à jour le compte actuel lors de la mise à jour
            //$model->compte_actuel = $model->compte_initial - $model->getMortaliteTotaleAttribute(); // Calculer le compte actuel
        //});
    }

    /**
     * Calculer la mortalité totale associée à cette bande.
     *
     * @return int
     */
    public function getMortaliteTotaleAttribute()
    {
        return $this->mortalites()->sum('mortalite_journaliere');
    }


/**
 * Calculer la quantité totale d'aliment consommée pour cette bande.
 *
 * @return float
 */
public function getQuantiteAlimentTotaleAttribute()
{
    return $this->feedConsumptions()->sum('quantite_aliment');
}




    // Définir la relation 'hasMany' avec le modèle Traitement
    public function traitements()
    {
        return $this->hasMany(Traitement::class);
    }



     // Définir la relation avec les dépenses
     public function expenses()
     {
         return $this->hasMany(Expense::class);
     }
 
     // Définir la relation avec les ventes
     public function sales()
     {
         return $this->hasMany(Sale::class);
     }
}