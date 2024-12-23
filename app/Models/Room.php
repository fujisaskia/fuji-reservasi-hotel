<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // app/Models/Room.php
    protected $fillable = [
        'room_number',
        'room_type_id',
        'room_status'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id'); // Gunakan koma untuk memisahkan parameter
    }    

    public function reservation()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_room', 'room_id', 'reservation_id');
    }
    
}