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
            // Check and add borrowing approval fields only if they don't exist
            if (!Schema::hasColumn('borrowings', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('borrowings', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approval_status');
            }
            if (!Schema::hasColumn('borrowings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            }
            
            // Check and add return approval fields only if they don't exist
            if (!Schema::hasColumn('borrowings', 'return_approval_status')) {
                $table->enum('return_approval_status', ['pending', 'approved', 'rejected'])->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('borrowings', 'return_rejection_reason')) {
                $table->text('return_rejection_reason')->nullable()->after('return_approval_status');
            }
            if (!Schema::hasColumn('borrowings', 'return_approved_at')) {
                $table->timestamp('return_approved_at')->nullable()->after('return_rejection_reason');
            }
            
            // Add column to track who should approve (the person who added the book)
            if (!Schema::hasColumn('borrowings', 'should_be_approved_by')) {
                $table->foreignId('should_be_approved_by')->nullable()->after('return_approved_at')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'rejection_reason',
                'approved_at',
                'return_approval_status',
                'return_rejection_reason',
                'return_approved_at',
                'should_be_approved_by'
            ]);
        });
    }
};
