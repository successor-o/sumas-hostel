<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained()->cascadeOnDelete();
            $table->string('room_number');
            $table->string('floor')->default('Ground Floor');
            $table->unsignedTinyInteger('capacity')->default(4);
            $table->enum('status', ['vacant', 'partial', 'full', 'maintenance'])->default('vacant');
            $table->timestamps();

            $table->unique(['hostel_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
