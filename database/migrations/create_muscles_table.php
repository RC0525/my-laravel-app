<?php
    use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('muscles', function (Blueprint $table) {
            $table->id();
            $table->string('name_zh', 100);       // 肌肉中文名稱 (例: 大腿後肌 / 腘绳肌)
            $table->string('name_en', 100);       // 英文名稱 / 學名 (例: Hamstrings)
            $table->string('body_part', 50);      // 身體區域 (head_neck, shoulder_upper_limb, spine_trunk, pelvic_lower_limb)
            $table->text('description')->nullable(); // 肌肉簡介與主要功能
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('muscles');
    }
};