<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routines', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('type');      // 確保有這行 (pnf, tut, posture)
    $table->string('category');  // 確保有這行 (chest, hamstrings, ucs, apt)
    $table->text('description')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('routines');
    }
};