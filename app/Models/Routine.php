<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'category',
        'description'
    ];

    public function phases()
    {
        return $this->hasMany(RoutinePhase::class);
    }
}