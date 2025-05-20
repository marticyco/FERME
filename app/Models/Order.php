<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'delivery_address',
        'order_date',
        'status',
        'price',
        'delivery_type',
        'scheduled_time',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function chickenTypes()
    {
        return $this->belongsToMany(ChickenType::class, 'chicken_type_order')
                    ->withPivot('quantity');
    }

    public function calculateOrderPrice()
    {
        $total = 0;
        foreach ($this->chickenTypes as $chickenType) {
            $quantity = $chickenType->pivot->quantity ?? 1;
            $total += $chickenType->price * $quantity;
        }
        return $total;
    }
}
