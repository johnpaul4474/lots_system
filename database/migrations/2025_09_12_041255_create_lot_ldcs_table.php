<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lot_ldcs', function (Blueprint $table) {
            $table->id();
             $table->string('lot_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('lot_ldcs');
    }
};
