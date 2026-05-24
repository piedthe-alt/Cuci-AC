<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPhoto extends Model
{
    protected $table = 'order_photos';
    
    protected $fillable = [
        'order_id',
        'type',
        'photo_path',
        'description',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
