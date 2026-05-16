<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya tambah jika kolom belum ada
            if (!Schema::hasColumn('users', 'gemini_api_key')) {
                $table->string('gemini_api_key')->nullable()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'gemini_api_key')) {
                $table->dropColumn('gemini_api_key');
            }
        });
    }
};