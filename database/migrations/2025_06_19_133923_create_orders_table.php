<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('no')->unique();
            $table->string('nama_customer');
            $table->foreignId('id_customer')->constrained('users')->onDelete('cascade');
            $table->json('pesanan');
            $table->bigInteger('total');
            $table->enum('metode', ['tunai', 'termin']);
            $table->enum('status', ['proses', 'dikirim', 'diterima', 'return'])->default('proses');
            $table->string('bukti_bayar')->nullable();
            $table->enum('status_bayar', ['belum bayar', 'lunas']);
            $table->string('file_faktur');
            $table->text('alasan_return')->nullable();
            $table->enum('status_return', ['proses', 'done'])->nullable();
            $table->string('status_nota')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
