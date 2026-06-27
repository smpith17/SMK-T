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
        // SESUAI TABEL 3.2 DI LAPORAN PKL
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary(); // 1. Diubah dari id() biasa ke UUID v4 [cite: 74, 79]
            $table->string('nama', 100);   // 2. Diubah dari 'name' menjadi 'nama' (maksimal 100 karakter) 
            $table->string('username', 50)->unique(); // 3. Diubah dari 'email' menjadi 'username' 
            $table->enum('role', ['satpam', 'cs', 'admin']); // 4. Ditambahkan kolom pilihan Role petugas 
            $table->string('password');    // 5. Tetap ada untuk menyimpan hash password 
            $table->tinyInteger('is_active')->default(1); // 6. Ditambahkan untuk status aktif/nonaktif akun 
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel bawaan token reset password (bisa dibiarkan tetap ada)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel bawaan session Laravel
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // TWEAK PENTING: foreignId diubah menjadi foreignUuid agar cocok karena tabel users di atas sudah pakai UUID 
            $table->foreignUuid('user_id')->nullable()->index(); 
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};