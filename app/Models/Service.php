<?php

namespace App\Models;
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural
    protected $table = 'services';

    // Tentukan kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'name',
        'service_category_id',
        'price',
    ];

    // Relasi ke ServiceCategory (belongsTo)
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

}

