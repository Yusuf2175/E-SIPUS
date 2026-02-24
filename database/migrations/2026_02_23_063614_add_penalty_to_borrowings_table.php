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
            $table->decimal('penalty_amount', 10, 2)->default(0)->after('return_notes');
            $table->enum('penalty_type', ['none', 'late', 'damaged', 'lost'])->default('none')->after('penalty_amount');
            $table->boolean('penalty_paid')->default(false)->after('penalty_type');
            $table->text('penalty_notes')->nullable()->after('penalty_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['penalty_amount', 'penalty_type', 'penalty_paid', 'penalty_notes']);
        });
    }
};
