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
        Schema::table('borrowings', function (Blueprint $table) {
            // Ubah kolom status untuk menambahkan status pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue'])
                  ->default('pending')
                  ->change();
            
            // Tambah kolom untuk alasan reject
            $table->text('reject_reason')->nullable()->after('notes');
            
            // Tambah kolom untuk tanggal approval
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('status', ['borrowed', 'returned', 'overdue'])
                  ->default('borrowed')
                  ->change();
            
            $table->dropColumn(['reject_reason', 'approved_at']);
        });
    }
};
