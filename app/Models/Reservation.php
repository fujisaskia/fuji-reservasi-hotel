<?php

namespace App\Models;

use App\Models\User;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id', // Kolom baru
        'room_type_id',
        'total_price',
        'total_guest', // Jumlah tamu
        'total_room', // Jumlah kamar
        'reservation_date',
        'check_in_date',
        'check_out_date',
        'payment_status',
        'reservation_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room()
    {
        return $this->belongsToMany(Room::class, 'reservation_room', 'reservation_id', 'room_id');
    }
    

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'reservation_id', 'id');
    }    


    public function payment()
    {
        return $this->hasOne(Payment::class, 'reservation_id');
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }



}
