<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hostel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hostel_application_id')->nullable()->constrained('hostel_applications')->nullOnDelete();
            $table->unsignedTinyInteger('bed_number');
            $table->string('session');
            $table->enum('status', ['active', 'vacated'])->default('active');
            $table->foreignId('allocated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('allocated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
