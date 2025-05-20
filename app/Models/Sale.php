<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'category_id', // ✅ Ajout de la colonne catégorie
        'montant',
        'description',
        'date',
    ];

    /**
     * Une vente peut appartenir à une bande (ou être globale)
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    /**
     * ✅ Une vente appartient à une catégorie (optionnelle)
     */
    public function category()
    {
        return $this->belongsTo(SaleCategory::class);
    }

    /**
     * Cast pour s'assurer que la date est bien formatée
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
