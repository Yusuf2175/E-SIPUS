<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum status untuk menambahkan pending_return
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'pending_return', 'returned', 'borrowed', 'overdue') DEFAULT 'pending'");
        
        // Tambah kolom untuk return request
        Schema::table('borrowings', function (Blueprint $table) {
            $table->timestamp('return_requested_at')->nullable()->after('returned_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('return_requested_at');
        });
        
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue') DEFAULT 'pending'");
    }
};
