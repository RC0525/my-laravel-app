<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stretch;

class PnfStep extends Model {
    use HasFactory;

    protected $table = 'pnf_steps';

    protected $fillable = [
        'stretch_id',
        'step_number',
        'step_name_zh',
        'instruction',
        'duration_seconds',
        'ui_color_accent',
    ];

    // 關聯：此步驟屬於哪一個 PNF 伸展動作
    public function stretch() {
        return $this->belongsTo(Stretch::class);
    }
}