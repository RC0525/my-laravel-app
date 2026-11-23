<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stretch;
use App\Models\PosturalDeviation;

class Muscle extends Model {
    use HasFactory;

    protected $table = 'muscles';

    protected $fillable = [
        'name_zh',
        'name_en',
        'body_part',
        'description',
    ];

    // 關聯：此肌肉對應的多個 PNF 伸展動作
    public function stretches() {
        return $this->hasMany(Stretch::class, 'target_muscle_id');
    }

    // 關聯：與體態偏差的多對多關聯
    public function posturalDeviations() {
        return $this->belongsToMany(PosturalDeviation::class, 'deviation_muscle')
                    ->withPivot('muscle_state');
    }
}