<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
    
return new class extends Migration {
    public function up(): void {
        Schema::create('deviation_stretch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postural_deviation_id')->constrained()->onDelete('cascade');
            $table->foreignId('stretch_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('priority_order')->default(1); // 推薦優先順序 (1=最推薦)
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('deviation_stretch');
    }
};