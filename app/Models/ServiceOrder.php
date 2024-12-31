<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'reservation_id',
        'service_id',
        'price',
        'total_price',
        'quantity',
        'order_date',
        'notes',
    ];

    // Relasi ke model Reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Relasi ke model Service
    public function service()
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }
}
