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
        Schema::create('log_audit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('timestamp')->useCurrent();
            $table->foreignUuid('user_id')->constrained('users'); // Siapa yang melakukan aksi 
            $table->foreignUuid('kartu_id')->constrained('kartu_tertelan'); // Kartu mana yang diubah 
            $table->enum('action', ['Input', 'Ubah_Status', 'Musnahkan']);
            $table->string('old_status', 20)->nullable();
            $table->string('new_status', 20);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent(); // Bersifat append-only (tidak ada updated_at) 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_audit');
    }
};
