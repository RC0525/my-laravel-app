<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutinePhase extends Model
{
    use HasFactory;

    protected $fillable = ['routine_id', 'phase_order', 'phase_name', 'duration_seconds', 'audio_cue'];

    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class);
    }
}