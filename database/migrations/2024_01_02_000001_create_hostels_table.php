<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['Male', 'Female', 'Postgraduate']);
            $table->string('warden')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['Active', 'Under Maintenance', 'Closed'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostels');
    }
};
