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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->string('no_batch');
            $table->enum('kemasan',  ['fls', 'btl', 'box', 'tube', 'strip']);
            $table->string('distributor');
            $table->string('pabrik');
            $table->integer('quantity');
            $table->integer('harga');
            $table->date('tanggal_masuk');
            $table->date('ed');
            $table->enum('status',  ['tersedia', 'habis', 'expired'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
