<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_obat',
        'no_batch',
        'kemasan',
        'distributor',
        'pabrik',
        'quantity',
        'harga',
        'tanggal_masuk',
        'ed',
        'status',
    ];
}
