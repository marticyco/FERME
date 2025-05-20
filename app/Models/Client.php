<?php
// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'address'
    ];

    /**
     * Relation avec les abonnements.
     * Un client peut avoir plusieurs abonnements.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Relation avec les commandes.
     * Un client peut avoir plusieurs commandes.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

