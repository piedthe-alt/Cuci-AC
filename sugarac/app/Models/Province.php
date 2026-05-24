<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name', 'code'];

    public function serviceTypeRegions()
    {
        return $this->hasMany(ServiceTypeRegion::class);
    }
}
