<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcModel extends Model
{
    protected $fillable = ['name', 'description'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
