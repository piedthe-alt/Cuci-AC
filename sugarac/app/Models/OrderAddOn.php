<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddOn extends Model
{
    protected $table = 'order_add_ons';
    
    protected $fillable = [
        'order_id',
        'add_on_id',
        'quantity',
        'unit_price',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function addOn()
    {
        return $this->belongsTo(AddOn::class);
    }
}
