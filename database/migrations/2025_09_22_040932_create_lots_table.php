<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->string('lot_number');

            // Foreign keys
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('lot_ldc')->nullable();
            $table->unsignedBigInteger('lot_map')->nullable();

            $table->timestamps();

            // Relationships
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
            $table->foreign('lot_ldc')->references('id')->on('lot_ldcs')->onDelete('set null');
            $table->foreign('lot_map')->references('id')->on('lot_maps')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
