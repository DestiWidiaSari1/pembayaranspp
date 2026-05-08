<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->string('bulan');
            $table->integer('tahun');
            $table->string('metode'); // tunai, transfer, qris
            $table->decimal('jumlah_bayar', 12, 2);
            $table->enum('status', ['lunas', 'belum', 'cicilan'])->default('belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
