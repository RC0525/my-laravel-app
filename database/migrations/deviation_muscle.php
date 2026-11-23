<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('deviation_muscle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postural_deviation_id')->constrained()->onDelete('cascade');
            $table->foreignId('muscle_id')->constrained()->onDelete('cascade');
            $table->enum('muscle_state', ['shortened', 'lengthened_weak']); // 縮短(緊繃) vs 拉長(無力)
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('deviation_muscle');
    }
};