<?php

// app/Models/Lot.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'nombre_males',
        'nombre_femelles',
    ];

    // Relations
    public function mortalites()
    {
        return $this->hasMany(Mortalite::class);
    }

           


               // Méthode pour calculer la mortalité totale
    public function mortaliteTotale()
    {
        return $this->mortalites->sum(function ($mortalite) {
            return $mortalite->nombre_morts;
        });
    }

    // Méthode pour calculer le taux de mortalité
    public function tauxMortalite()
    {
        // Total des reproducteurs (mâles + femelles)
        $totalReproducteurs = $this->nombre_males + $this->nombre_femelles;

        // Si le total des reproducteurs est supérieur à 0, calculer le taux de mortalité
        if ($totalReproducteurs > 0) {
            // Calcul du taux de mortalité
            $totalMortalite = $this->mortaliteTotale();
            return ($totalMortalite / $totalReproducteurs) * 100;
        }

        // Si aucun reproducteur, retourner 0
        return 0;
    }
        /**
     * La relation entre un lot et ses pontes.
     */
    

    public function pontes()
    {
        return $this->hasMany(Ponte::class);
    }

    /**
     * Calculer la mortalité journalière du lot
     */
    public function dailyMortalityRate($lotId)
    {
        return Mortalite::where('lot_id', $lotId) // Utilisation de lotId
                        ->whereDate('date_mortalite', today())
                        ->count();
    }

    /**
     * Calculer le taux de mortalité du lot
     */
    public function mortalityRate($lotId)
    {
        $lot = Lot::findOrFail($lotId);  // Utilisation de lotId
        $total_individus = $lot->nombre_males + $lot->nombre_femelles;
        $morts = Mortalite::where('lot_id', $lotId)->count();

        return $total_individus > 0 ? round(($morts / $total_individus) * 100, 2) : 0;
    }

    /**
     * Calculer le nombre d'œufs pondus aujourd'hui par le lot
     */
    public function dailyEggs($lotId)
    {
        return Ponte::where('lot_id', $lotId)  // Utilisation de lotId
                    ->whereDate('date_ponte', today())
                    ->sum('nombre_oeufs');
    }

    /**
     * Calculer le taux de ponte quotidien du lot
     */
    public function dailyEggRate($lotId)
{
    // Récupérer le lot
    $lot = Lot::findOrFail($lotId);
    $femalesCount = $lot->nombre_femelles;

    // Vérifiez si les femelles sont supérieures à 0
    if ($femalesCount <= 0) {
        return 0; // Si aucune femelle, taux de ponte = 0
    }

    // Récupérer le nombre d'œufs produits aujourd'hui
    $dailyEggs = $this->dailyEggs($lotId);

    // Vérifiez les données avant le calcul du taux
    if ($dailyEggs <= 0) {
        return 0; // Si aucun œuf produit, taux de ponte = 0
    }

    // Calcul du taux de ponte : (nombre d'œufs / nombre de femelles) * 100
    return round(($dailyEggs / $femalesCount) * 100, 2);
}

    /**
     * Calculer le ratio mâles/femelles du lot
     */
    public function maleToFemaleRatio($lotId)
    {
        $lot = Lot::findOrFail($lotId);  // Utilisation de lotId
        $malesCount = $lot->nombre_males;
        $femalesCount = $lot->nombre_femelles;

        return $femalesCount > 0 ? round($malesCount / $femalesCount, 2) : 0;
    }

    /**
     * Calculer le taux de ponte mensuel du lot
     */
    public function monthlyEggRate($lotId)
    {
        $monthlyEggs = Ponte::where('lot_id', $lotId)  // Utilisation de lotId
                            ->whereMonth('date_ponte', now()->month)
                            ->sum('nombre_oeufs');
        $lot = Lot::findOrFail($lotId);  // Utilisation de lotId
        $femalesCount = $lot->nombre_femelles;

        return $femalesCount > 0 ? round(($monthlyEggs / ($femalesCount * now()->daysInMonth)) * 100, 2) : 0;
    }

    public function totalEggsLaid($lotId)
    {
        // Somme des œufs pondus dans le lot, pour toutes les dates et tous les enregistrements de Ponte associés
        return Ponte::where('lot_id', $lotId)  // Utilisation de lotId
                    ->sum('nombre_oeufs');
    }

    /**
     * Calculer la performance globale du lot
     */
    public function getPerformance($lotId)
    {
        return [
            'mortalite_journaliere' => $this->dailyMortalityRate($lotId),
            'taux_mortalite' => $this->mortalityRate($lotId),
            'nombre_oeufs_journaliers' => $this->dailyEggs($lotId),
            'taux_ponte_journalier' => $this->dailyEggRate($lotId),
            'ratio_males_femelles' => $this->maleToFemaleRatio($lotId),
            'taux_ponte_mensuel' => $this->monthlyEggRate($lotId),
            'total_egg_laid' => $this->totalEggsLaid($lotId),
        ];
    }
}
