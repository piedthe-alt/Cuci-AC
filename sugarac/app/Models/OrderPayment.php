<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payments';
    
    protected $fillable = [
        'order_id',
        'total_amount',
        'amount_paid',
        'payment_method',
        'status',
        'bank_name',
        'account_number',
        'account_holder',
        'payment_notes',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
