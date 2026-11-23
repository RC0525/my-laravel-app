<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stretch extends Model
{
    protected $guarded = [];

    /**
     * 關聯到目標肌肉 (Target Muscle)
     */
    public function targetMuscle(): BelongsTo
    {
        return $this->belongsTo(Muscle::class, 'target_muscle_id');
    }

    /**
     * 關聯到 PNF 步驟
     */
    public function pnfSteps()
    {
        return $this->hasMany(PnfStep::class, 'stretch_id');
    }
    protected function casts(): array
    {
        return [
            'ai_rules' => 'array',
        ];
    }
}