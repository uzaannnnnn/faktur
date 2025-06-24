<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $kemasanList = ['fls', 'btl', 'box', 'tube', 'strip'];

        for ($i = 1; $i <= 10; $i++) {
            $tanggalMasuk = Carbon::now()->subDays(rand(1, 30));
            $ed = $tanggalMasuk->copy()->addMonths(3);

            $quantity = rand(0, 100);
            if ($quantity == 0) {
                $status = 'habis';
            } elseif ($ed->lessThan(Carbon::today())) {
                $status = 'expired';
            } else {
                $status = 'tersedia';
            }

            $noBatch = 'OBT' . $tanggalMasuk->format('ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

            DB::table('obats')->insert([
                'no_batch' => $noBatch,
                'nama_obat' => 'Obat ' . $i,
                'kemasan' => $kemasanList[array_rand($kemasanList)],
                'distributor' => 'Distributor ' . $i,
                'pabrik' => 'Pabrik ' . $i,
                'quantity' => $quantity,
                'harga' => 5000,
                'tanggal_masuk' => $tanggalMasuk,
                'ed' => $ed,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
