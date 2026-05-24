<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTypeRegion extends Model
{
    protected $fillable = ['service_type_id', 'province_id', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
