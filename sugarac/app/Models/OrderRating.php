<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRating extends Model
{
    protected $table = 'order_ratings';
    
    protected $fillable = [
        'order_id',
        'user_id',
        'staff_id',
        'rating',
        'review',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
