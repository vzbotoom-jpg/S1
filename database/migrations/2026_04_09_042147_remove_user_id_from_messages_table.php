<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Cek apakah foreign key ada sebelum dihapus
            $foreignKeys = $this->getForeignKeys('messages');
            
            if (in_array('messages_user_id_foreign', $foreignKeys)) {
                $table->dropForeign('messages_user_id_foreign');
            }
            
            // Cek apakah kolom user_id ada sebelum dihapus
            if (Schema::hasColumn('messages', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
    
    private function getForeignKeys($table)
    {
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();
        $tableName = $table;
        
        $results = $conn->select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE REFERENCED_TABLE_SCHEMA = ? 
            AND TABLE_NAME = ?
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$dbName, $tableName]);
        
        return array_column($results, 'CONSTRAINT_NAME');
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
        });
    }
};