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
        'service_checked_at',
        'work_completed_at',
        'payment_completed_at',
        'rated_at',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'assigned_at' => 'datetime',
        'service_checked_at' => 'datetime',
        'work_completed_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'rated_at' => 'datetime',
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

    // Relationships untuk workflow
    public function photos()
    {
        return $this->hasMany(OrderPhoto::class);
    }

    public function addOns()
    {
        return $this->hasMany(OrderAddOn::class);
    }

    public function payment()
    {
        return $this->hasOne(OrderPayment::class);
    }

    public function rating()
    {
        return $this->hasOne(OrderRating::class);
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

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'menunggu' => 'Menunggu',
            'ditugaskan' => 'Ditugaskan',
            'cek_layanan' => 'Cek Layanan',
            'pengerjaan' => 'Pengerjaan',
            'payment' => 'Pembayaran',
            'selesai' => 'Selesai',
            default => $this->status,
        };
    }

    /**
     * Get status badge CSS class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'menunggu' => 'bg-yellow-100 text-yellow-800',
            'ditugaskan' => 'bg-blue-100 text-blue-800',
            'cek_layanan' => 'bg-purple-100 text-purple-800',
            'pengerjaan' => 'bg-orange-100 text-orange-800',
            'payment' => 'bg-red-100 text-red-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
