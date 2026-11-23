<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stretches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_muscle_id')->constrained('muscles')->onDelete('cascade');
            $table->string('title_zh', 150);
            $table->string('title_en', 150)->nullable();
            $table->string('equipment_needed')->default('none');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->text('safety_warnings');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stretches');
    }
};