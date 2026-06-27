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
        Schema::create('kartu_tertelan', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Sesuai laporan: UUID v4 
            $table->string('nomor_kartu', 4); // Data masking 4 digit terakhir 
            $table->string('nama_nasabah', 100);
            $table->string('lokasi_atm', 50);
            $table->enum('lokasi_simpan', ['Kantor Pusat', 'Cabang', 'Capem']);
            $table->dateTime('tanggal_masuk')->useCurrent();
            $table->dateTime('deadline'); // Nanti dihitung otomatis di Controller 
            $table->enum('status', ['Disimpan', 'Dihubungi', 'Diambil', 'Dimusnahkan'])->default('Disimpan');
            $table->foreignUuid('input_oleh')->constrained('users'); // Relasi ke users.id 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_tertelan');
    }
};
