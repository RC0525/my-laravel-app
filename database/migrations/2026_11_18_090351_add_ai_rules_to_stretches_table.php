<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stretches', function (Blueprint $table) {
            // 儲存 MediaPipe 關節編號、目標角度、比較運算子與額外姿勢條件
            $table->json('ai_rules')->nullable()->after('title_zh');
        });
    }

    public function down(): void
    {
        Schema::table('stretches', function (Blueprint $table) {
            $table->dropColumn('ai_rules');
        });
    }
};