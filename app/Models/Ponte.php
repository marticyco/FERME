<?php
// app/Models/Ponte.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponte extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_id',
        //'femelle_id',
        'nombre_oeufs',
        'date_ponte',
    ];

    // Relation avec Lot
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    







}

