<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routine;
use App\Models\WorkoutLog;
use App\Models\PosturalDeviation;
use App\Models\Stretch;

class FitnessController extends Controller
{
    // 渲染前端首頁並載入資料庫中的訓練菜單與 PNF / 體態矯正資料
    public function index()
    {
        // 1. 載入原本 RoutineSeeder 的所有訓練菜單
        $routines = Routine::with('phases')->get();
        
        // 2. 確保 PNF 與 Posture 練習項目能完整顯示原本豐富的清單
        $pnfRoutines = $routines->where('type', 'pnf');
        $tutRoutines = $routines->where('type', 'tut');
        $postureRoutines = $routines->where('type', 'posture');

        // 3. 載入完整的體態偏差詳細資料（供關聯與進階模組使用）
        $deviations = PosturalDeviation::with([
            'shortenedMuscles', 
            'recommendedStretches.pnfSteps',
            'recommendedStretches.targetMuscle'
        ])->get();

        // 傳遞給前端的 welcome 檢視畫面
        return view('welcome', compact('routines', 'pnfRoutines', 'tutRoutines', 'postureRoutines', 'deviations'));
    }

    // 接收前端提交的訓練日誌與 SUS 評分並存入 SQLite
    public function storeLog(Request $request)
    {
        $validated = $request->validate([
            'routine_id' => 'nullable|exists:routines,id',
            'sus_score' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
            'metrics' => 'nullable|array',
        ]);

        $log = WorkoutLog::create([
            'user_id' => 1, // 預設測試使用者
            'routine_id' => $validated['routine_id'] ?? null,
            'sus_score' => $validated['sus_score'] ?? null,
            'feedback' => $validated['feedback'] ?? null,
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Workout log and SUS feedback successfully saved to SQLite!',
            'data' => $log
        ]);
    }
}