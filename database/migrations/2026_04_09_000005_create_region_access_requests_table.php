<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('region_access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('requested_region');   // provinsi yang diminta aksesnya
            $table->string('user_province');       // provinsi asal user
            $table->text('reason')->nullable();    // alasan request
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Satu user hanya bisa punya satu request aktif per region
            $table->unique(['user_id', 'requested_region', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('region_access_requests');
    }
};
