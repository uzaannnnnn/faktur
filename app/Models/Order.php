<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'nama_customer',
        'id_customer',
        'pesanan',
        'total',
        'metode',
        'status',
        'status_bayar',
        'bukti_bayar',
        'file_faktur'
    ];

    protected $casts = [
        'pesanan' => 'array',
        'created_at' => 'datetime',
    ];

    // Relasi ke user (customer)
    public function customer()
    {
        return $this->belongsTo(User::class, 'id_customer');
    }

    /**
     * Generate Nomor Faktur
     * Format: ddmmyyyy + urutan (4 digit)
     */
    public static function generateNo()
    {
        $today = Carbon::now()->format('dmY');

        // Hitung berapa order hari ini
        $countToday = self::whereDate('created_at', Carbon::today())->count();
        $urutan = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);

        return $today . $urutan;
    }

    /**
     * Generate Nama File Faktur
     * Format: no-namacustomer.pdf
     */
    public static function generateFileFaktur($no, $nama_customer)
    {
        $nama_singkat = preg_replace('/[^a-zA-Z0-9]/', '', str_replace(' ', '', $nama_customer));
        return $no . '-' . $nama_singkat . '.pdf';
    }

    /**
     * Menentukan Status Pembayaran
     */
    public static function determineStatusBayar($metode, $bukti_bayar)
    {
        return ($metode === 'termin' && is_null($bukti_bayar)) ? 'belum bayar' : 'lunas';
    }
}
