<?php

namespace Tests\Unit;

use App\Models\Kratos;
use App\Models\Monster;
use App\Services\DamageCalculator;
use PHPUnit\Framework\TestCase;

class DamageCalculatorTest extends TestCase
{
    public function test_damage_is_attacker_strength_minus_defender_defence(): void
    {
        $attacker = $this->makeKratosWithStats(strength: 80, defence: 40);
        $defender = $this->makeMonsterWithStats(strength: 60, defence: 30);

        $this->assertSame(50, DamageCalculator::calculate($attacker, $defender));
    }

    public function test_damage_is_zero_when_defence_exceeds_strength(): void
    {
        $attacker = $this->makeKratosWithStats(strength: 40, defence: 50);
        $defender = $this->makeMonsterWithStats(strength: 50, defence: 80);

        $this->assertSame(0, DamageCalculator::calculate($attacker, $defender));
    }

    public function test_damage_is_zero_when_strength_equals_defence(): void
    {
        $attacker = $this->makeKratosWithStats(strength: 50, defence: 40);
        $defender = $this->makeMonsterWithStats(strength: 60, defence: 50);

        $this->assertSame(0, DamageCalculator::calculate($attacker, $defender));
    }

    public function test_damage_is_always_non_negative(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $attacker = new Kratos();
            $defender = new Monster();
            $damage   = DamageCalculator::calculate($attacker, $defender);
            $this->assertGreaterThanOrEqual(0, $damage);
        }
    }

    // helpers

    private function makeKratosWithStats(int $strength, int $defence): Kratos
    {
        $k           = new Kratos();
        $k->strength = $strength;
        $k->defence  = $defence;
        return $k;
    }

    private function makeMonsterWithStats(int $strength, int $defence): Monster
    {
        $m           = new Monster();
        $m->strength = $strength;
        $m->defence  = $defence;
        return $m;
    }
}
