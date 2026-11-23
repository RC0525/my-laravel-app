<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void {
        Schema::create('postural_deviations', function (Blueprint $table) {
            $table->id();
            $table->string('title_zh', 150);      // 體態偏差 / 症狀名稱 (例: 前頭姿勢 / Forward Head Posture)
            $table->string('title_en', 150)->nullable();
            $table->enum('category', ['spine', 'pelvis_lower_limb', 'shoulder_upper_limb', 'pain_symptom', 'lower_limb']); // 分類
            $table->text('assessment_guide');     // 自我檢測引導與表徵說明
            $table->text('severity_tips')->nullable(); // 輕重度提醒或警訊 (Red Flags)
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('postural_deviations');
    }
};