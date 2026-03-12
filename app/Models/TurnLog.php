<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TurnLog extends Model
{
    protected $fillable = [
        'battle_id',
        'turn_number',
        'attacker',
        'skill_used',
        'damage_dealt',
        'was_lucky_miss',
        'defender_health_remaining',
    ];

    protected $casts = [
        'was_lucky_miss' => 'boolean',
    ];

    public function battle(): BelongsTo
    {
        return $this->belongsTo(BattleLog::class, 'battle_id');
    }
}
