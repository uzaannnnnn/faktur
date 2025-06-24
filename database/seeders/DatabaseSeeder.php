<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\ObatSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'alamat' => 'Jl.Kenangan 81, Bandung',
            'tagihan' => 0,
        ]);

        User::create([
            'name' => 'Gudang User',
            'email' => 'gudang@example.com',
            'password' => Hash::make('password'),
            'role' => 'gudang',
        ]);

        User::create([
            'name' => 'Fakturis User',
            'email' => 'fakturis@example.com',
            'password' => Hash::make('password'),
            'role' => 'fakturis',
        ]);
        $this->call(ObatSeeder::class);
    }
}
