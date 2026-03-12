<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BattleLog extends Model
{
    protected $fillable = [
        'kratos_health',
        'kratos_strength',
        'kratos_defence',
        'kratos_speed',
        'kratos_luck',
        'monster_health',
        'monster_strength',
        'monster_defence',
        'monster_speed',
        'monster_luck',
        'winner',
        'total_turns',
    ];

    public function turns(): HasMany
    {
        return $this->hasMany(TurnLog::class, 'battle_id');
    }
}
