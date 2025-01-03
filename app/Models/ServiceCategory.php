<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural
    protected $table = 'service_categories';

    // Tentukan kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'name',
        'description',
    ];

    // Tentukan kolom yang tidak boleh diisi
    protected $guarded = [];

    // Menambahkan timestamp jika dibutuhkan, jika tidak cukup menghapus properti timestamps
    public $timestamps = true;
}

