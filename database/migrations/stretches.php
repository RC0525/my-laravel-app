<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


   return new class extends Migration {
    public function up(): void {
        Schema::create('stretches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_muscle_id')->constrained('muscles')->onDelete('cascade');
            $table->string('title_zh', 150);       // 動作名稱 (例: 仰臥毛巾拉胸大肌)
            $table->string('title_en', 150)->nullable();
            $table->string('equipment_needed')->default('none'); // 所需輔助器材 (例: 毛巾, 牆壁, 椅子)
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->text('safety_warnings');       // 安全提示與禁忌症 (例: 避免關節過度壓迫)
            $table->string('image_path')->nullable(); // 預覽圖片或 2D 示意圖路徑
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stretches');
    }
};
