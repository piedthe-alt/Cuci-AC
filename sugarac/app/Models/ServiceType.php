<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = ['service_id', 'name', 'description', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function regions()
    {
        return $this->hasMany(ServiceTypeRegion::class);
    }

    /**
     * Get price by province, or default price if not set
     */
    public function getPriceByProvince($provinceId)
    {
        $regionPrice = $this->regions()
            ->where('province_id', $provinceId)
            ->first();

        return $regionPrice ? $regionPrice->price : $this->price;
    }
}
