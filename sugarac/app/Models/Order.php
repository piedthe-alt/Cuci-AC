<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_staff_id',
        'ac_model_id',
        'service_type_id',
        'units',
        'phone',
        'address',
        'latitude',
        'longitude',
        'visit_date',
        'notes',
        'total_price',
        'status',
        'assigned_at',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'assigned_at' => 'datetime',
        'total_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function acModel()
    {
        return $this->belongsTo(AcModel::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    // Scope untuk filtering order user
    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk filtering order assigned staff
    public function scopeAssignedTo($query, $staffId)
    {
        return $query->where('assigned_staff_id', $staffId);
    }

    // Scope untuk menampilkan order yang belum di-assign
    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_staff_id');
    }

    // Scope untuk menampilkan order yang sudah di-assign
    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_staff_id');
    }
}
