<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pnf_steps', function (Blueprint $table) {
            if (!Schema::hasColumn('pnf_steps', 'step_name_en')) {
                $table->string('step_name_en')->nullable()->after('step_name_zh');
            }
            if (!Schema::hasColumn('pnf_steps', 'instruction_en')) {
                $table->text('instruction_en')->nullable()->after('instruction');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pnf_steps', function (Blueprint $table) {
            $table->dropColumn(['step_name_en', 'instruction_en']);
        });
    }
};