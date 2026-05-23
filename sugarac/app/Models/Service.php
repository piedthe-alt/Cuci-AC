<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description'];

    public function serviceTypes()
    {
        return $this->hasMany(ServiceType::class);
    }
}
