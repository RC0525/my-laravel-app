<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pnf_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stretch_id')->constrained('stretches')->onDelete('cascade');
            $table->tinyInteger('step_number');    // 階段號碼 (1 至 6)
            $table->string('step_name_zh', 100);   // 階段名稱 (例: 等長收縮, 第二次放鬆)
            $table->text('instruction');           // 具體動作指引
            $table->unsignedInteger('duration_seconds')->default(0); // 建議維持時間 (秒)
            $table->string('ui_color_accent', 20)->default('#E0AC69'); // UI 主題色調
            $table->timestamps();

            $table->unique(['stretch_id', 'step_number']); // 確保同一動作的步驟編號不重複
        });
    }

    public function down(): void {
        Schema::dropIfExists('pnf_steps');
    }
};