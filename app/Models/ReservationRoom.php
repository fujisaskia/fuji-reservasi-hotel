<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRoom extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'reservation_room';

    // Kolom yang dapat diisi
    protected $fillable = [
        'reservation_id',
        'room_id',
    ];

    // Relasi dengan model Reservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Relasi dengan model Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
