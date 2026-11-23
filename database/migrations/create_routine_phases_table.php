<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routine_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_id')->constrained()->cascadeOnDelete();
            $table->integer('phase_order');
            $table->string('name');              // <-- 確保有這行！
            $table->integer('duration_seconds');
            $table->text('instruction');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routine_phases');
    }
};