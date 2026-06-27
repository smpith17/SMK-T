<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Menyuntikkan kolom last_used_at jika belum ada
            if (!Schema::hasColumn('personal_access_tokens', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('abilities');
            }
            // Menyuntikkan kolom expires_at jika belum ada (antisipasi error selanjutnya)
            if (!Schema::hasColumn('personal_access_tokens', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('last_used_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn(['last_used_at', 'expires_at']);
        });
    }
};