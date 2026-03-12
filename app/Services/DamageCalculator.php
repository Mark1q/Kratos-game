<?php

namespace App\Services;

use App\Models\Fighter;

class DamageCalculator
{
    /**
     * Base damage formula: attacker strength - defender defence
     * Minimum 0 (can't heal the enemy)
     */
    public static function calculate(Fighter $attacker, Fighter $defender): int
    {
        return max(0, $attacker->strength - $defender->defence);
    }
}
