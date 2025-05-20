<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incubation extends Model
{
    use HasFactory;

    // Définir les attributs pouvant être remplis via un formulaire
    protected $fillable = [
        'date_debut',
        'nombre_oeufs_incubes',
        'temperature',
        'humidite',
        'date_mirage',
        'date_fin_eclosion',
        'nombre_oeufs_fertiles',
        'nombre_oeufs_fertiles_eclos',
    ];

    // Format de date de début
    protected $casts = [
        'date_debut' => 'date',
        'date_mirage' => 'date',
        'date_fin_eclosion' => 'date',
    ];

    /**
     * Calculer le taux de fertilité (nombre d'œufs fertiles divisé par nombre d'œufs incubés).
     * 
     * @return float
     */
    public function calculateTauxFertilite()
    {
        if ($this->nombre_oeufs_incubes > 0 && $this->nombre_oeufs_fertiles !== null) {
            return ($this->nombre_oeufs_fertiles / $this->nombre_oeufs_incubes) * 100;
        }

        return 0;
    }

    /**
     * Calculer le taux d'éclosion (nombre d'œufs éclos divisé par nombre d'œufs fertiles).
     * 
     * @return float
     */
    public function calculateTauxEclosion()
    {
        if ($this->nombre_oeufs_fertiles > 0 && $this->nombre_oeufs_fertiles_eclos !== null) {
            return ($this->nombre_oeufs_fertiles_eclos / $this->nombre_oeufs_fertiles) * 100;
        }

        return 0;
    }

    /**
     * Accessor pour obtenir la date de début sous un format spécifique.
     * Utilisation de DateTime au lieu de Carbon.
     *
     * @return string
     */
    public function getDateIncubationAttribute()
    {
        $date_debut = new \DateTime($this->date_debut);
        return $date_debut->format('d/m/Y');
    }

    /**
     * Accessor pour obtenir le taux de fertilité sous forme d'un pourcentage.
     *
     * @return float
     */
    public function getTauxFertiliteAttribute()
    {
        return $this->calculateTauxFertilite();
    }

    /**
     * Accessor pour obtenir le taux d'éclosion sous forme d'un pourcentage.
     *
     * @return float
     */
    public function getTauxEclosionAttribute()
    {
        return $this->calculateTauxEclosion();
    }

    /**
     * Accessor pour obtenir le statut de l'incubation.
     * Cela peut être "En cours", "Terminée", "Échec", etc.
     *
     * @return string
     */
    public function getStatutAttribute()
    {
        // Vérifier la date de fin d'éclosion
        if ($this->date_fin_eclosion) {
            $date_fin_eclosion = new \DateTime($this->date_fin_eclosion);
            if ($date_fin_eclosion < new \DateTime()) {
                return 'Terminée';
            }
        }
        
        // Vérifier la date de mirage
        if ($this->date_mirage) {
            $date_mirage = new \DateTime($this->date_mirage);
            if ($date_mirage <= new \DateTime()) {
                return 'En cours';
            }
        }

        return 'Planifiée';
    }

    /**
     * Calculer la date de mirage (10 jours après la date de début).
     *
     * @return \DateTime
     */
    public function calculateDateMirage()
    {
        $date_debut = new \DateTime($this->date_debut);
        $date_debut->modify('+10 days');
        return $date_debut;
    }

    /**
     * Calculer la date de fin d'éclosion (22 jours après la date de début).
     *
     * @return \DateTime
     */
    public function calculateDateFinEclosion()
    {
        $date_debut = new \DateTime($this->date_debut);
        $date_debut->modify('+22 days');
        return $date_debut;
    }
}
