<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// 1. 確保正確引入 Muscle 與 Stretch Model
use App\Models\Muscle;
use App\Models\Stretch;

class PosturalDeviation extends Model {
    use HasFactory;

    // 獲取需要拉伸的緊繃肌肉 (Shortened Muscles)
    public function shortenedMuscles() {
        return $this->belongsToMany(Muscle::class, 'deviation_muscle')
                    ->wherePivot('muscle_state', 'shortened');
    }

    // 獲取該體態偏差推薦的所有 PNF 伸展動作
    public function recommendedStretches() {
        return $this->belongsToMany(Stretch::class, 'deviation_stretch')
                    ->withPivot('priority_order')
                    ->orderBy('priority_order'); // 2. 修正這裡的排序欄位（移除 pivot_ 前綴）
    }
}