<?php

namespace Tests\Unit;

use App\Skills\MagicArmour;
use PHPUnit\Framework\TestCase;

class MagicArmourTest extends TestCase
{
    private MagicArmour $skill;

    protected function setUp(): void
    {
        $this->skill = new MagicArmour();
    }

    public function test_name_is_magic_armour(): void
    {
        $this->assertSame('Magic Armour', $this->skill->getName());
    }

    public function test_applies_on_defence(): void
    {
        $this->assertTrue($this->skill->appliesOnDefence());
    }

    public function test_does_not_apply_on_attack(): void
    {
        $this->assertFalse($this->skill->appliesOnAttack());
    }

    public function test_modify_defence_halves_even_damage(): void
    {
        $this->assertSame(20, $this->skill->modifyDefence(40));
    }

    public function test_modify_defence_rounds_up_on_odd_damage(): void
    {
        $this->assertSame(13, $this->skill->modifyDefence(25));
    }

    public function test_modify_defence_with_one_damage(): void
    {
        $this->assertSame(1, $this->skill->modifyDefence(1));
    }

    public function test_modify_defence_with_zero_damage(): void
    {
        $this->assertSame(0, $this->skill->modifyDefence(0));
    }

    public function test_modify_defence_always_less_than_or_equal_to_original(): void
    {
        foreach ([10, 25, 50, 99, 100] as $damage) {
            $this->assertLessThanOrEqual($damage, $this->skill->modifyDefence($damage));
        }
    }

    public function test_modify_attack_is_a_passthrough(): void
    {
        $this->assertSame([50], $this->skill->modifyAttack(50));
    }

    public function test_triggers_returns_bool(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $this->assertIsBool($this->skill->triggers());
        }
    }

    public function test_triggers_eventually_returns_true(): void
    {
        $triggered = false;
        for ($i = 0; $i < 200; $i++) {
            if ($this->skill->triggers()) {
                $triggered = true;
                break;
            }
        }
        $this->assertTrue($triggered, 'MagicArmour should trigger at least once in 200 attempts');
    }
}
