<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitnessController;

// 載入首頁
fn() => route('home'); // 確保路由正確指向
Route::get('/', [FitnessController::class, 'index']);

// 接收前端日誌提交的 API 路由
Route::post('/api/workout-logs', [FitnessController::class, 'storeLog']);
Route::get('/ai-assessment', function () {
    return view('ai-assessment');
})->name('ai.assessment');




use App\Models\PosturalDeviation;

Route::get('/', function () {
    $deviations = PosturalDeviation::with('recommendedStretches')->get();
    return view('welcome', compact('deviations')); // 假設你的主頁是 welcome.blade.php 或 app.blade.php
});