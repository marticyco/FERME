<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMortality extends Model
{
    use HasFactory;

    // Définissez les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'band_id',
        'date',
        'mortalite_journaliere',
    ];

    /**
     * Relation avec le modèle Band.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    /**
     * Règles de validation pour les mortalités journalières.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'date' => 'required|date',
            'mortalite_journaliere' => 'required|integer|min:0',
        ];
    }


// Lors de la création d'une nouvelle mortalité, mettre à jour le compte_actuel de la bande
protected static function boot()
{
    parent::boot();

    static::created(function ($mortality) {
        // Mise à jour du compte actuel de la bande après l'ajout de la mortalité
        $band = $mortality->band;
        $band->compte_actuel = $band->compte_initial - $band->mortalites()->sum('mortalite_journaliere');
        $band->save();
    });
}



}
