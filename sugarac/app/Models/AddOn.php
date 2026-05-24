<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    protected $table = 'add_ons';
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'unit',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orderAddOns()
    {
        return $this->hasMany(OrderAddOn::class);
    }
}
