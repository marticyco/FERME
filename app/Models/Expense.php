<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'category_id', // 🆕 Ajout de la catégorie
        'montant',
        'description',
        'date',
    ];

    /**
     * Une dépense peut appartenir à une bande (ou être globale)
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    /**
     * ✅ Une dépense appartient à une catégorie
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    /**
     * Formatage automatique de la date
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
